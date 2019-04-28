<html>
<head><title>Maze</title><meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" /></head>
<body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0" style="font: 14px "Lucida Grande", Helvetica, Arial, sans-serif;">
	<div style="width: 100%; background: transparent; display: table; background: #4cbbd6;">
		<div style="text-align: center; margin-top: 20px;">
			<img src="{{ $base_url }}/images/logo-min.png" height="70" alt="Serapina" />
		</div>
		<div style="width: 75%; background: #FFF; margin: 20px 10% 20px 10%; padding: 10px;">
			<h2>{{ $subject }}</h2>
			<br/>
			
			Your <a href="{{ $base_url }}"><b>Maze</b></a> account has been created. Please click the link below to verify your email.

			<center>
				<br/>
				<a href="{{ $base_url }}/auth/verifyEmail?confirm={{ $user->email_verification_code }}" style="cursor: pointer; text-decoration: none;">
					<div style="width: 75%; border-radius: 2px; background: #398BCE; padding: 20px; color: #FFF; cursor: pointer;">
						CONFIRM EMAIL
					</div>
				</a>
				<br/>
			</center>
			
			<br/><br/>
			Happy Mazing,
			<br/>
			Team Maze
		</div>
		<div style="width: 75%: margin: 20px 10% 20px 10%; font: 11px Tahoma, Arial;">
			<center>
				<span style="color: #FFF;">&copy; Maze</span>
				<br/><br/>
			</center>
		</div>
	</div>
</body>
</html>