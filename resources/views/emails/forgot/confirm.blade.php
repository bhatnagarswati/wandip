<!DOCTYPE html>
<html>
<head>
<title>Welcome Email</title>
</head>
<body>

<p>Dear {{ $user->username }},</p>

<p>You have recently registered to join the “I am” by Infobeing community.  Please verify your account by clicking on the link:</p>


<p><b><a href="<?php echo @$link; ?>">Link</a></b></p>
 
<p>We welcome you into our community with open arms. Have fun!</p>


Thanks

Iam Team
</body>
</html>