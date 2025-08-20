<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>INQUIRY #</strong></th>
            <th><strong>COMPANY NAME</strong></th>
            <th><strong>PASSENGER NAME</strong></th>
            <th><strong>PASSENGER EMAIL</strong></th>
            <th><strong>PASSENGER PHONE</strong></th>
            <th><strong>CURRENT STATUS</strong></th>
            <th><strong>CURRENT STATUS COMMENTS</strong></th>
            <th><strong>CURRENT STATUS ON</strong></th>
            <th><strong>CURRENT STATUS BY</strong></th>
            <th><strong>SOURCE</strong></th>
            <th><strong>FLIGHT TYPE</strong></th>
            <th><strong>AIRLINE</strong></th>
            <th><strong>CABIN CLASS</strong></th>
            <th><strong>DEPARTURE AIRPORT</strong></th>
            <th><strong>DEPARTURE DATE</strong></th>
            <th><strong>ARRIVAL AIRPORT</strong></th>
            <th><strong>ARRIVAL DATE</strong></th>
            <th><strong>NIGHTS IN MAKKAH</strong></th>
            <th><strong>NIGHTS IN MADINA</strong></th>
            <th><strong># OF ADULT TRAVELERS</strong></th>
            <th><strong># OF CHILD TRAVELERS</strong></th>
            <th><strong># OF INFINT TRAVELERS</strong></th>
            <th><strong>ASSIGNED TO</strong></th>
            <th><strong>ASSIGNED ON</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($inquiries as $inquiry)
        @php
            $recentStatusDetails = ($inquiry->inquiryAssigments && $inquiry->inquiryAssigments->actions)
                ? $inquiry->inquiryAssigments->actions->last()
                : null;
            $recentStatus = $recentStatusDetails ? $recentStatusDetails->inquiry_status : '-';
            $recentStatusComments = $recentStatusDetails ? $recentStatusDetails->comments : '-';
            $recentStatusOn = $recentStatusDetails ? $recentStatusDetails->created_at : '-';
            $recentStatusBy = $recentStatusDetails ? $recentStatusDetails->created_by : '-';
        @endphp
        <tr>
            <td>{{ $inquiry->id }}</td>
            <td>{{ $inquiry->company_name }}</td>
            <td>{{ $inquiry->lead_passenger_name }}</td>
            <td>{{ $inquiry->email }}</td>
            <td>{{ $inquiry->contact_number }}</td>
            <td>{{ $recentStatus }}</td>
            <td>{{ $recentStatusComments }}</td>
            <td>{{ ($recentStatusDetails != null) ? date("M-d-Y", strtotime($recentStatusOn)) : '-' }}</td>
            <td>{{ ($recentStatusDetails != null) ? userDetails($recentStatusBy)->name : '-' }}</td>
            <td>{{ $inquiry->source }}</td>
            <td>{{ $inquiry->flight_type }}</td>
            <td>{{ $inquiry->airline }}</td>
            <td>{{ $inquiry->cabin_class }}</td>
            <td>{{ $inquiry->departure_airport }}</td>
            <td>{{ $inquiry->departure_date }}</td>
            <td>{{ $inquiry->arrival_airport }}</td>
            <td>{{ $inquiry->arrival_date }}</td>
            <td>{{ $inquiry->nights_in_makkah }}</td>
            <td>{{ $inquiry->nights_in_madina }}</td>
            <td>{{ $inquiry->no_of_adult_travelers }}</td>
            <td>{{ $inquiry->no_of_child_travelers }}</td>
            <td>{{ $inquiry->no_of_infant_travelers }}</td>
            <td>{{ ($inquiry->inquiry_assigned_to != null) ? userDetails($inquiry->inquiry_assigned_to)->name : "ASSIGNMENT PENDING" }}</td>
            <td>{{ ($inquiry->inquiry_assignment_on != null) ? $inquiry->inquiry_assignment_on : "ASSIGNMENT PENDING" }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
