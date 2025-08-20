{{-- resources/views/emails/booking_notifications.blade.php --}}
@php
    use Illuminate\Support\Arr;
    use Carbon\Carbon;

    // ---------- Utilities ----------
    $toArray = function ($data) {
        if ($data instanceof \Illuminate\Support\Collection) return $data->toArray();
        if (is_object($data) && method_exists($data, 'toArray')) return $data->toArray();
        if (is_object($data)) return json_decode(json_encode($data), true);
        return is_array($data) ? $data : [];
    };

    $isAssoc = function ($arr) {
        if (!is_array($arr)) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    };

    $prettify = function ($key) {
        // support snake_case and camelCase
        $key = preg_replace('/(?<!^)([A-Z])/', ' $1', $key);     // split camel
        $key = str_replace('_', ' ', $key);
        return ucwords(trim($key));
    };

    $looksLikeDate = function ($val) {
        if (!is_string($val)) return false;
        // quick checks: 2025-07-30, 30-07-2025, 2025/07/30, 30/07/2025, ISO with time
        return preg_match('/^\d{4}[-\/]\d{1,2}[-\/]\d{1,2}(\s+\d{1,2}:\d{2}(:\d{2})?)?$/', $val) ||
               preg_match('/^\d{1,2}[-\/]\d{1,2}[-\/]\d{4}(\s+\d{1,2}:\d{2}(:\d{2})?)?$/', $val);
    };

    $formatVal = function ($key, $val) use ($looksLikeDate) {
        if (is_null($val) || $val === '') return '—';
        if (is_bool($val)) return $val ? 'Yes' : 'No';
        if (is_array($val)) return json_encode($val, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if (is_object($val)) return json_encode($val, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        // numeric / currency-like fields
        $k = strtolower($key);
        $currencyHints = ['amount','price','cost','total','net','sale','fare','tax','fee'];
        if (is_numeric($val) && collect($currencyHints)->contains(function ($hint) use ($k) { return str_contains($k, $hint); })) {
            return number_format((float)$val, 2);
        }
        // dates
        if ($looksLikeDate($val)) {
            try {
                return Carbon::parse($val)->format('Y-m-d H:i');
            } catch (\Throwable $e) {
                // fall through
            }
        }
        return (string)$val;
    };

    $flatten = function ($record) {
        // Flatten nested arrays/objects to dot notation so we can compare easily
        $arr = is_array($record) ? $record : (is_object($record) ? json_decode(json_encode($record), true) : []);
        return Arr::dot($arr);
    };

    $identifierFor = function ($record) {
        // Choose a friendly title for a record (hotel name, supplier, visa type, etc.)
        $candidates = ['name','title','hotel_name','supplier','vehicle_type','visa_type','room_type','airline','segment','route','reference','pnr','ticket','number','id'];
        $flat = Arr::dot(is_array($record) ? $record : (array)$record);
        foreach ($candidates as $c) {
            foreach ($flat as $k => $v) {
                if (str_ends_with($k, $c) || $k === $c) {
                    if (!is_array($v) && !is_object($v) && $v !== '' && $v !== null) {
                        return (string)$v;
                    }
                }
            }
        }
        // fallback
        return 'Record';
    };

    // keys to exclude from diff
    $excluded = [
        'id','booking_id','created_at','updated_at','deleted_at',
        'created_by','updated_by','pivot'
    ];

    $shouldExclude = function ($key) use ($excluded) {
        $base = strtolower(last(explode('.', $key))); // base key (after flatten)
        return in_array($base, $excluded, true);
    };

    // ---------- Normalize input ----------
    $old = $toArray($oldData);
    $new = $toArray($newData);

    // Detect whether data is a list of records or a single record
    $oldList = is_array($old) && !$isAssoc($old) ? $old : (empty($old) ? [] : [$old]);
    $newList = is_array($new) && !$isAssoc($new) ? $new : (empty($new) ? [] : [$new]);

    // Index by id when possible, else by sequence index
    $indexBy = function ($list) {
        $indexed = [];
        foreach ($list as $i => $row) {
            $key = null;
            if (is_array($row) && array_key_exists('id', $row)) $key = 'id:'.$row['id'];
            elseif (is_object($row) && isset($row->id)) $key = 'id:'.$row->id;
            $indexed[$key ?? ('row:'.($i+1))] = $row;
        }
        return $indexed;
    };

    $oldIdx = $indexBy($oldList);
    $newIdx = $indexBy($newList);

    $allRecordKeys = collect(array_unique(array_merge(array_keys($oldIdx), array_keys($newIdx))))->values();

    // Build diff buckets
    $changed = [];  // recordKey => [ ['field'=>, 'old'=>, 'new'=>], ... ]
    $added   = [];  // recordKey => record
    $removed = [];  // recordKey => record

    foreach ($allRecordKeys as $rk) {
        $o = $oldIdx[$rk] ?? null;
        $n = $newIdx[$rk] ?? null;

        if ($o && !$n) {             // removed
            $removed[$rk] = $o;
            continue;
        }
        if ($n && !$o) {             // added
            $added[$rk] = $n;
            continue;
        }
        // both exist -> field-level diff
        $oFlat = $flatten($o);
        $nFlat = $flatten($n);
        $allFields = collect(array_unique(array_merge(array_keys($oFlat), array_keys($nFlat))))->values();
        $rows = [];
        foreach ($allFields as $field) {
            if ($shouldExclude($field)) continue;
            $ov = $oFlat[$field] ?? null;
            $nv = $nFlat[$field] ?? null;
            // Normalize empty strings vs null as equal?
            $ovNorm = ($ov === '' ? null : $ov);
            $nvNorm = ($nv === '' ? null : $nv);
            if ($ovNorm == $nvNorm) continue; // unchanged

            $rows[] = [
                'field' => $field,
                'label' => $prettify($field),
                'old'   => $ov,
                'new'   => $nv,
            ];
        }
        if (!empty($rows)) $changed[$rk] = $rows;
    }

    // For headings: compute a friendly name per record
    $labelForKey = function ($rk, $record) use ($identifierFor) {
        $hint = $identifierFor($record);
        return ($rk ?? 'Record') . ($hint && $hint !== 'Record' ? ' — '.$hint : '');
    };
@endphp

<h2>Booking #{{ $bookingNumber ?? '' }} — ({{ $bookingService ?? '' }}) Updates</h2>

{{-- Changed Records --}}
@if(!empty($changed))
    <h3 style="margin-top:18px;">Changed ({{ $bookingService ?? '' }}) on {{ now()->format('Y-m-d H:i:s') }} by {{ Auth::user()->name }}</h3>
    @foreach($changed as $rk => $rows)
        @php
            $recTitle = isset($newIdx[$rk]) ? $labelForKey($rk, $newIdx[$rk]) : $rk;
        @endphp
        <h4 style="margin:10px 0 6px;">{{ $recTitle }}</h4>
        <table width="100%" border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th align="left">Field</th>
                    <th align="left">Old</th>
                    <th align="left">New</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr>
                        <td>{{ $r['label'] }}</td>
                        <td>{{ $formatVal($r['field'], $r['old']) }}</td>
                        <td>{{ $formatVal($r['field'], $r['new']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif

{{-- Added Records --}}
@if(!empty($added))
    <h3 style="margin-top:18px;">Added</h3>
    @foreach($added as $rk => $rec)
        @php
            $flat = $flatten($rec);
        @endphp
        <h4 style="margin:10px 0 6px;">{{ $labelForKey($rk, $rec) }}</h4>
        <table width="100%" border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th align="left">Field</th>
                    <th align="left">Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flat as $k => $v)
                    @continue($shouldExclude($k))
                    <tr>
                        <td>{{ $prettify($k) }}</td>
                        <td>{{ $formatVal($k, $v) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif

{{-- Removed Records --}}
@if(!empty($removed))
    <h3 style="margin-top:18px;">Removed</h3>
    @foreach($removed as $rk => $rec)
        @php
            $flat = $flatten($rec);
        @endphp
        <h4 style="margin:10px 0 6px;">{{ $labelForKey($rk, $rec) }}</h4>
        <table width="100%" border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
            <thead>
                <tr>
                    <th align="left">Field</th>
                    <th align="left">Value (Previously)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flat as $k => $v)
                    @continue($shouldExclude($k))
                    <tr>
                        <td>{{ $prettify($k) }}</td>
                        <td>{{ $formatVal($k, $v) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
@endif

{{-- Fallback when nothing changed --}}
@if(empty($changed) && empty($added) && empty($removed))
    <p>No changes detected.</p>
@endif

<p style="margin-top:16px;">
    For more details, please log in to the <a href="https://mis.bestumrahpackagesuk.com" target="_blank" rel="noopener">MIS</a>.
</p>
