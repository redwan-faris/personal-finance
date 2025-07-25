<!DOCTYPE html>
<html>
<head>
    <title>Company Approval Notification</title>
</head>
<body>
    <p>Dear {{ $data['full_name'] }},</p>
    <p>We are pleased to inform you can participate in {{ $data['exam_name'] }} exam at {{ $data['exam_date'] }}!</p>
    <p>Here are your credentials to join the exam:</p>
    <ul>
        <li>email : {{ $data['email'] }}</li>
        <li>password : {{ $data['password'] }}</li>
    </ul>
    <p>If you have any questions, feel free to reach out.</p>
    <p>Best regards,</p>
</body>
</html>
