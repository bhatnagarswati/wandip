<!DOCTYPE html>
<html>
<head>
    <title>Contact us Query Email</title>
</head>
<body>
    <table cellspacing="0" border="0" align="center" cellpadding="0" width="800" style="border:1px solid #ccc; margin-top:10px;float: left;">
        <tr>
            <td>
                <table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">

                    <tr align="center">

                        <td style="font-family:arial; padding-bottom:40px;"><strong>
							<img src="{{ url('public/images').'/'.'logo.png' }}" alt="logo.png"  style="max-width: 100%;" />
							</strong>
						</td>
                    </tr>
                </table>
                <table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="border:0px solid #efefef; margin-top:0px; padding:40px;">
                    <tr>
                        <td>
                            <h2>Hello Admin,</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4 >Inquiry Message received from the AdBlue website</h4>
                            <b>Name : </b> {{ $firstName }} {{ $lastName }} <br/><br/>
                            <b>Email :</b> {{ $uEmail }}<br/><br/>
                            <b>Contact Number : </b> {{ $phoneNumber }} <br/><br/>
                            <b>Message : </b> {{ $uQuery }}<br/><br/>
                            </td>
						</tr>
						<tr>
							<td>
								<table cellspacing="0" border="0" cellpadding="0" width="100%">	
									<tr>
										<td><h3>Regards,</h3>
											<h3>Adblue Team</h3>
										</td>
									</tr>
								</table>
							</td>
							<td width="30"></td> 
						</tr>
					</table>
					<table cellspacing="0" border="0" align="center" cellpadding="0" width="100%" style="border:0px solid #efefef; margin-top:20px; padding:0px;">
						<tr>
							<td align="center" style="font-family:PT Sans,sans-serif; font-size:13px; padding:15px 0; border-top:1px solid #efefef;"> 
							<b>Copyright © AdBlue®/DEF. All Rights Reserved.</b></strong></td> 
						</tr>
					</table>
				</td>   
			</tr>
		</table>
</body>
</html>