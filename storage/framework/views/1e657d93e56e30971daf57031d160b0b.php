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
        <?php $__currentLoopData = $inquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $recentStatusDetails = ($inquiry->inquiryAssigments && $inquiry->inquiryAssigments->actions)
                ? $inquiry->inquiryAssigments->actions->last()
                : null;
            $recentStatus = $recentStatusDetails ? $recentStatusDetails->inquiry_status : '-';
            $recentStatusComments = $recentStatusDetails ? $recentStatusDetails->comments : '-';
            $recentStatusOn = $recentStatusDetails ? $recentStatusDetails->created_at : '-';
            $recentStatusBy = $recentStatusDetails ? $recentStatusDetails->created_by : '-';
        ?>
        <tr>
            <td><?php echo e($inquiry->id); ?></td>
            <td><?php echo e($inquiry->company_name); ?></td>
            <td><?php echo e($inquiry->lead_passenger_name); ?></td>
            <td><?php echo e($inquiry->email); ?></td>
            <td><?php echo e($inquiry->contact_number); ?></td>
            <td><?php echo e($recentStatus); ?></td>
            <td><?php echo e($recentStatusComments); ?></td>
            <td><?php echo e(($recentStatusDetails != null) ? date("M-d-Y", strtotime($recentStatusOn)) : '-'); ?></td>
            <td><?php echo e(($recentStatusDetails != null) ? userDetails($recentStatusBy)->name : '-'); ?></td>
            <td><?php echo e($inquiry->source); ?></td>
            <td><?php echo e($inquiry->flight_type); ?></td>
            <td><?php echo e($inquiry->airline); ?></td>
            <td><?php echo e($inquiry->cabin_class); ?></td>
            <td><?php echo e($inquiry->departure_airport); ?></td>
            <td><?php echo e($inquiry->departure_date); ?></td>
            <td><?php echo e($inquiry->arrival_airport); ?></td>
            <td><?php echo e($inquiry->arrival_date); ?></td>
            <td><?php echo e($inquiry->nights_in_makkah); ?></td>
            <td><?php echo e($inquiry->nights_in_madina); ?></td>
            <td><?php echo e($inquiry->no_of_adult_travelers); ?></td>
            <td><?php echo e($inquiry->no_of_child_travelers); ?></td>
            <td><?php echo e($inquiry->no_of_infant_travelers); ?></td>
            <td><?php echo e(($inquiry->inquiry_assigned_to != null) ? userDetails($inquiry->inquiry_assigned_to)->name : "ASSIGNMENT PENDING"); ?></td>
            <td><?php echo e(($inquiry->inquiry_assignment_on != null) ? $inquiry->inquiry_assignment_on : "ASSIGNMENT PENDING"); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/exports/inquiries.blade.php ENDPATH**/ ?>