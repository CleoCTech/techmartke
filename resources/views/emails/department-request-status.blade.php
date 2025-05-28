<!DOCTYPE html>
<html>
<head>
    <title>Department Request Status Update</title>
</head>
<body>
    <p>Hello,</p>
    <p>Your department request has been updated.</p>

    <p><strong>Request Title:</strong> {{ $requestTitle }}</p>
    <p><strong>Status:</strong> {{ $requestStatus }}</p>
    @if ($remarks)
        <p><strong>Remarks:</strong> {{ $remarks }}</p>
    @endif
    <p><strong>To Department:</strong> {{ $toDepartment }}</p>

    <p>Thank you,<br>Church Management System</p>
</body>
</html>
