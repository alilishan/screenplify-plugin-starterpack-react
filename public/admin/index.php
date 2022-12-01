<?php 	

	
	if(!isset($_GET['component_id']) || $_GET['component_id'] == ''){
		echo 'Widget ID is Missing!'; 
		exit;
	}

	$widgetId = $_GET['component_id'];
	$jsonFile = "../data.json";
	$dataFile = file_get_contents($jsonFile);
	$dataArray = (array) json_decode($dataFile, true);
	$reservedKeys = ['format', 'u', 'a', 'k', 's'];
	$queryString = http_build_query(array(
			'component_id' => $widgetId,
			'layout_id' => (isset($_GET['layout_id'])) ? $_GET['layout_id'] : '',
			'screen_id' => (isset($_GET['screen_id'])) ? $_GET['screen_id'] : ''
		));

	$saveSuccess = false;

	// Process for New Set to be Generated with Defaults or Update Keys in Old Set
	$dataSet = (isset($dataArray[$widgetId])) ? $dataArray[$widgetId] : array();

	foreach ($dataArray['defaults'] as $key => $value) {
		if(!in_array($key, $reservedKeys)){
			
			$dataSet[$key] = (isset($dataSet[$key])) ? $dataSet[$key] : $value;

		}
	}

	// print_r($dataSet); exit;

	// If Form Post
	if(@$_POST){

        // Capture Post Data
        $postData = $_POST;

        // Fix Values
        $postData['showQRCode'] = (isset($_POST['showQRCode']))? TRUE : FALSE;
        $postData['animationInterval'] = (int)$postData['animationInterval'];
        $postData['refreshTimer'] = (int)$postData['refreshTimer'];
        
		// // Merge the two
		$finalDataset = array_merge($dataSet, $postData);

		// Reasign to Data Array
		$dataArray[$widgetId] = $finalDataset;

		// Write Back to File
		$fp = fopen($jsonFile, 'w');
		fwrite($fp, json_encode($dataArray, JSON_PRETTY_PRINT));
		fclose($fp);

		$saveSuccess = true;
	}

    $fontSizes = ['12px', '14px', '16px', '18px', '20px' ];
    $animationIn = ['animate__fadeIn','animate__fadeInDownBig','animate__fadeInLeftBig','animate__fadeInRightBig','animate__fadeInUpBig','animate__fadeInTopLeft','animate__fadeInTopRight','animate__fadeInBottomLeft','animate__fadeInBottomRight'];
    $animationOut = ['animate__fadeOut','animate__fadeOutDownBig','animate__fadeOutLeftBig','animate__fadeOutRightBig','animate__fadeOutUpBig','animate__fadeOutTopLeft','animate__fadeOutTopRight','animate__fadeOutBottomLeft','animate__fadeOutBottomRight'];

    function animationName($name){
        return str_replace("animate__", "", $name);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RichRSS Plugin 1.0 | Screenplify</title>

    <link rel="stylesheet" href="assets/bootstrap-4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap-select@1.13.14/bootstrap-select.min.css">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="pt-4 pb-5">
	
	<?php /* ?>
	<nav id="main-nav" class="navbar fixed-top navbar-light">
        <a class="navbar-brand ml-auto mr-auto" href="./">
            <img src="../images/logo-sm.png" alt="">
        </a>
    </nav>
	<?php */ ?>

	<div class="container">
		<div class="row">
	 		<div class="col-12 col-md-8 offset-md-2">
				
				<nav class="navbar navbar-light bg-white rounded-lg shadow mb-4">
					<a class="navbar-brand ml-auto mr-auto" href="./">
			            <img src="../favicons/logo-sm.png" alt="">
			        </a>
				</nav>

				<div class="card border-0 rounded-lg text-secondary shadow">
					<div class="card-body">
						<h4 class="ls-8">Settings</h4>
						<hr>
						
						<?php if($saveSuccess){ ?>
						<div class="alert alert-success">Successfully Saved</div>
						<?php } ?>

						<form method="POST" action="index.php?<?php echo $queryString; ?> ">

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Feed URL</label>
								<div class="col-sm-8">
									<input type="text" required name="URL" value="<?php echo $dataSet['URL']; ?>" class="form-control">
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">BG Color</label>
								<div class="col-sm-2">
									<input type="color" class="form-control" name="backgroundColor" value="<?php echo $dataSet['backgroundColor']; ?>">
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Text Color</label>
								<div class="col-sm-2">
									<input type="color" class="form-control" name="textColor" value="<?php echo $dataSet['textColor']; ?>">
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Text Size</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="textSize">
                                        <?php 
                                            foreach ($fontSizes as $key => $value) {
                                                $selected = ($dataSet['textSize'] == $value)? "selected" : "";
                                                echo '<option value="'.$value.'" '.$selected.' >'.$value.'</option>';
                                            }
                                        ?>
									</select>
								</div>
							</div>

                            
							<div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Font</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="fontFace">
										<option value="Poppins" <?php if($dataSet['fontFace'] == 'Poppins'){ echo 'selected'; }?>>Poppins</option>
										<option value="OpenSans" <?php if($dataSet['fontFace'] == 'OpenSans'){ echo 'selected'; }?>>OpenSans</option>
										<option value="Roboto" <?php if($dataSet['fontFace'] == 'Roboto'){ echo 'selected'; }?>>Roboto</option>
									</select>
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Animation In</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="animationIn">
                                        <?php 
                                            foreach ($animationIn as $key => $value) {
                                                $selected = ($dataSet['animationIn'] == $value)? "selected" : "";
                                                $anim = animationName($value);
                                                echo '<option value="'.$value.'" '.$selected.' >'.$anim.'</option>';
                                            }
                                        ?>
									</select>
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Animation Out</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="animationOut">
                                        <?php 
                                            foreach ($animationOut as $key => $value) {
                                                $selected = ($dataSet['animationOut'] == $value)? "selected" : "";
                                                $anim = animationName($value);
                                                echo '<option value="'.$value.'" '.$selected.' >'.$anim.'</option>';
                                            }
                                        ?>
									</select>
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Slide Delay</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="animationInterval">
										<option value="<?php echo 4 * 1000; ?>" <?php if($dataSet['animationInterval'] == 4 * 1000){ echo 'selected'; } ?> >4 seconds</option>
										<option value="<?php echo 5 * 1000; ?>" <?php if($dataSet['animationInterval'] == 5 * 1000){ echo 'selected'; } ?> >5 seconds</option>
										<option value="<?php echo 6 * 1000; ?>" <?php if($dataSet['animationInterval'] == 6 * 1000){ echo 'selected'; } ?> >6 seconds</option>
										<option value="<?php echo 7 * 1000; ?>" <?php if($dataSet['animationInterval'] == 7 * 1000){ echo 'selected'; } ?> >7 seconds</option>
										<option value="<?php echo 8 * 1000; ?>" <?php if($dataSet['animationInterval'] == 8 * 1000){ echo 'selected'; } ?> >8 seconds</option>
										<option value="<?php echo 9 * 1000; ?>" <?php if($dataSet['animationInterval'] == 9 * 1000){ echo 'selected'; } ?> >9 seconds</option>
										<option value="<?php echo 10 * 1000; ?>" <?php if($dataSet['animationInterval'] == 10 * 1000){ echo 'selected'; } ?> >10 seconds</option>
										<option value="<?php echo 11 * 1000; ?>" <?php if($dataSet['animationInterval'] == 11 * 1000){ echo 'selected'; } ?> >11 seconds</option>
										<option value="<?php echo 12 * 1000; ?>" <?php if($dataSet['animationInterval'] == 12 * 1000){ echo 'selected'; } ?> >12 seconds</option>
									</select>
								</div>
							</div>

                            <div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Update Every</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="refreshTimer">
										<option value="<?php echo 1 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 1){ echo 'selected'; } ?> >1 Hr</option>
										<option value="<?php echo 2 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 2){ echo 'selected'; } ?> >2 Hrs</option>
										<option value="<?php echo 3 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 3){ echo 'selected'; } ?> >3 Hrs</option>
										<option value="<?php echo 4 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 4){ echo 'selected'; } ?> >4 Hrs</option>
										<option value="<?php echo 5 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 5){ echo 'selected'; } ?> >5 Hrs</option>
										<option value="<?php echo 6 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 6){ echo 'selected'; } ?> >6 Hrs</option>
										<option value="<?php echo 7 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 7){ echo 'selected'; } ?> >7 Hrs</option>
										<option value="<?php echo 8 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 8){ echo 'selected'; } ?> >8 Hrs</option>
										<option value="<?php echo 9 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 9){ echo 'selected'; } ?> >9 Hrs</option>
										<option value="<?php echo 10 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 10){ echo 'selected'; } ?> >10 Hrs</option>
										<option value="<?php echo 11 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 11){ echo 'selected'; } ?> >11 Hrs</option>
										<option value="<?php echo 12 * 3.6e+6; ?>" <?php if($dataSet['refreshTimer'] / 3.6e+6 == 12){ echo 'selected'; } ?> >12 Hrs</option>
									</select>
								</div>
							</div>

							<div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right">Image Placement</label>
								<div class="col-sm-5">
									<select class="form-control selectpicker" name="imagePlacement">
										<option value="left" <?php if($dataSet['imagePlacement'] == 'left'){ echo 'selected'; }?>>Left</option>
										<option value="right" <?php if($dataSet['imagePlacement'] == 'right'){ echo 'selected'; }?>>Right</option>
									</select>
								</div>
							</div>

							<div class="form-group row py-2">
								<label class="col-sm-3 col-form-label text-left text-md-right"></label>
								<div class="col-sm-5">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="showQRCode" name="showQRCode" <?php if($dataSet['showQRCode']){ echo 'checked'; } ?>>
										<label class="custom-control-label" for="showQRCode">Show QR Code</label>
									</div>
								</div>
							</div>


							<div class="form-footer border-top text-right rounded-lg pt-3">
								<button id="btn-close" class="btn btn-link float-left">Close</button>
								<button class="btn btn-success" type="submit">Save Changes</button>
							</div>
							

						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
	

	<script type="text/javascript" src="assets/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-4.4.1/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-select@1.13.14/bootstrap-select.min.js"></script>
	<script>

		//Close Btns
		var btn = document.getElementById("btn-close");
		
		btn.addEventListener('click', function(e){
			e.preventDefault();
			var msg = {
				message: 'MODMGR_CLOSE',
				reload: false
			}
			window.parent.postMessage(msg, '*');
			return false;
		}, false);

	</script>


</body>
</html>

<?php  
function code_to_country( $code ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    if( !$countryList[$code] ) return $code;
    else return strtoupper($countryList[$code]);
}
?>