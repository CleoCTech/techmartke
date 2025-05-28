<!DOCTYPE html>
<html>
<head>
    <title>New Department Request</title>
</head>
<body>
    <p>Hello,</p>
    <p>A new request has been made to your department:</p>

    <p><strong>Request Title:</strong> {{ $requestTitle }}</p>
    <p><strong>Description:</strong> {{ $requestDescription }}</p>
    <p><strong>From Department:</strong> {{ $fromDepartment }}</p>
    <p><strong>To Department:</strong> {{ $toDepartment }}</p>

    <p>Please log in to review the request.</p>

    <p>Best Regards,<br>Church Management System</p>
</body>
</html>
