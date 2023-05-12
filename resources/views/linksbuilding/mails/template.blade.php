<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="format-detection" content="date=no" />
	<meta name="format-detection" content="address=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="x-apple-disable-message-reformatting" />
	<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,700,700i" rel="stylesheet"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<title>Mailing</title>
	<style type="text/css" media="screen">
		/* Linked Styles */
		body { padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#001736; -webkit-text-size-adjust:none }
		a { color:#66c7ff; text-decoration:none }
		p { padding:0 !important; margin:0 0 15px 0 !important }
		img { -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */ }
		.mcnPreviewText { display: none !important; }
		.text-success{color: #28a745 !important;}
		/* Mobile styles */
		@media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
			.mobile-shell { width: 100% !important; min-width: 100% !important; }
			.bg { background-size: 100% auto !important; -webkit-background-size: 100% auto !important; }
			.text-header,
			.m-center { text-align: center !important; }
			.center { margin: 0 auto !important; }
			.container { padding: 20px 10px !important }
			.td { width: 100% !important; min-width: 100% !important; }
			.m-br-15 { height: 15px !important; }
			.p30-15 { padding: 30px 15px !important; }
			.m-td,
			.m-hide { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }
			.m-block { display: block !important; }
			.fluid-img img { width: 100% !important; max-width: 100% !important; height: auto !important; }
			.column,
			.column-top,
			.column-empty,
			.column-empty2,
			.column-dir-top { float: left !important; width: 100% !important; display: block !important; }
			.column-empty { padding-bottom: 10px !important; }
			.column-empty2 { padding-bottom: 30px !important; }
			.content-spacing { width: 15px !important; }
		}
	</style>
</head>
<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#001736; -webkit-text-size-adjust:none;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#001736">
		<tr>
			<td align="center" valign="top">
				<table border="0" cellspacing="0" cellpadding="0" class="mobile-shell" style="width: <?php echo(@$size!='')? $size :'650px'?>;">
					<tr>
						<td align="center" class="td container" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; margin:0; font-weight:normal; padding:55px 0px;">
							<!-- Header -->
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td  class="p30-15" style="padding: 0px 30px 30px 30px;">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<th class="column-top" width="145" style="font-size:0pt; line-height:0pt; padding:0; margin:0; vertical-align:top;">
													<table border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="img m-center" style="text-align:left;">
																<img width="50" height="38" border="0" src="{{ asset('images/logo.svg') }}">
															</td>
															<td>
																<span style="margin: 0px; padding:0px; font-size:20px; color: white; font-family:'Muli', Arial,sans-serif;">{{ env('APP_NAME') }}</span>
															</td>
														</tr>
													</table>
												</th>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<!-- END Header -->

							<!-- Intro -->
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding-bottom: 10px;">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td class="tbrr p30-15" style="padding: 60px 30px; border-radius:25px;" bgcolor="white">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">

														@if (!empty(@$reset))
															<tr>
																<td align="left" style="padding: 0px; color: black; font-family:'Muli', Arial,sans-serif; font-size: 18px; font-weight: 400; line-height:2;">
																	<p style="margin: 0;">@lang('Hi'), <strong>{{ $name }}</strong></p>
																</td>
															</tr>
															<tr>
																<td align="left" style="padding-bottom: 20px; color: black; font-family:'Muli', Arial,sans-serif; font-size: 18px; font-weight: 400; line-height:2;">
																	<p style="margin: 0;">@lang('You are receiving this email because we received a password reset request for your account.')</p>
																</td>
															</tr>
														@endif

														@if (!empty(@$icon))
															<tr>
																<td align="center" style="padding: 0px 30px 0px 30px; color: #666666; font-size: 18px; font-weight: 400; line-height: 25px;">
																	<i class="fa fa-check-circle fa-5x text-success"></i>
																</td>
															</tr>
														@endif

														@if (!empty(@$content))
															<tr>
																<td class="pb25" style="color:black; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:1.5; text-align: <?php echo (@$align != '') ? $align : 'left' ?> ; padding:20px 0 30px;">
																	{!! $content !!}
																</td>
															</tr>
														@endif

														@if (!empty(@$password) && !empty($email))
															<tr>
																<td class="pb25" style="color:black; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:2; text-align: <?php echo (@$align != '') ? $align : 'left' ?> ; padding-bottom:25px;">
																	<strong>@lang('Credentials')</strong>
																</td>
															</tr>
															<tr>
																<td class="pb25" style="color:black; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:2; text-align: <?php echo (@$align != '') ? $align : 'left' ?> ; padding-bottom:10px;">
																	<strong>@lang('Email') :</strong> {!! $email !!}
																</td>
															</tr>
															<tr>
																<td class="pb25" style="color:black; font-family:'Muli', Arial,sans-serif; font-size:16px; line-height:2; text-align: <?php echo (@$align != '') ? $align : 'left' ?> ; padding-bottom:30px;">
																	<strong>@lang('Password') : </strong> {!! $password !!}
																</td>
															</tr>
														@endif

														@if (!empty(@$link))
															<!-- Button -->
																<tr>
																	<td align="center">
																		<table class="center" border="0" cellspacing="0" cellpadding="0" style="text-align:center;">
																			<tr>
																				<td class="pink-button text-button" style="background:#0064fb; color:#c1cddc; font-family:'Muli', Arial,sans-serif; font-size:18px; line-height:25px; padding:12px 30px; text-align:center; border-radius:0px 22px 22px 22px; font-weight:bold;">
																					<a href="{{ $link }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">@lang($link_text)</span></a>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															<!-- END Button -->
														@endif

														@if (!empty(@$reset))
															<tr>
																<td align="left" style="padding: 40px 0px 20px 0px; color: black; font-family:'Muli', Arial,sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
																	<p style="margin: 0;">@lang('This password reset link will expire in 60 minutes.') @lang('If you did not request a password reset, no further action is required.')</p>
																</td>
															</tr>
														@endif

														@if (!empty(@$code))
															<tr>
																<td align="center" style="font-size:0px;padding:10px 25px;padding-bottom:40px;word-break:break-word;">
																	<div style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:32px;font-weight:bold;line-height:1;text-align:center;color:black;">
																		{{__('Verification code')}}
																	</div>
																</td>
															</tr>
															<tr>
																<td align="center" style="font-size:0px;padding:10px 25px;padding-bottom:0;word-break:break-word;">
																	<div style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:16px;line-height:22px;text-align:center;color:black;">
																		{{__('To continue with the payment process, please enter the following verification code in the system, we have also sent you an attached pdf file with the general conditions that you are accepting')}}
																	</div>
																</td>
															</tr>
															<tr>
																<td align="center" style="font-size:0px;padding:10px 25px;padding-top:30px;padding-bottom:40px;word-break:break-word;">
																	<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
																		<tr>
																			<td align="center" bgcolor="#eeeeee" role="presentation" style="border:none;border-radius:3px;color:#ffffff;cursor:auto;padding:15px 25px;" valign="middle">
																				<div style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:40px;font-weight:bold;line-height:1;letter-spacing: 4px;text-align:center;color:#555;">
																					{{ $code }}
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														@endif
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<!-- END Intro -->

							<!-- Footer -->
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td class="text-footer3 p30-15" style="padding: 40px 30px 0px 30px; color:#475c77; font-family:'Muli', Arial,sans-serif; font-size:12px; line-height:18px; text-align:center;">
										<p style="margin: 0;">@lang('Copyright') {{ date('Y') }}
											<a href="{{ config('app.url') }}" target="_blank" class="link2-u" style="color:#475c77; text-decoration:underline;">{{ config('app.name') }}
											</a>.
										</p>
									</td>
								</tr>
							</table>
							<!-- END Footer -->
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
