<?php

/*
  Plugin Name: RemitRadar Remittance Calculator
  Plugin URI: https://wordpress.org/plugins/remitradar-remittance-calculator
  Description: RemitRadar Tools For Webmasters.
  Version: 1.2.2
  Author: remitradar.com
  Text Domain: remitradar-remittance-calculator
  Domain Path: /languages
  Author URI: https://remitradar.com/Home/Webmasters
 */


function my_plugin_load_plugin_textdomain() {
    load_plugin_textdomain( 'remitradar-remittance-calculator', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_plugin_load_plugin_textdomain' );

add_action('admin_menu', function(){
	add_menu_page( 'Remitradar widgets Settings', 'Remitradar widgets Settings', 'manage_options', 'remitradar-widgets-options', 'add_rrw_setting', '', 150 ); 
} );

add_action( 'widgets_init', function(){
		register_widget( 'RemitRadar_Widget' );
});


function rrw_loadMyScript() {

wp_register_style('rrw-style', plugins_url('css/style.css', __FILE__));
wp_register_style('bootstrap', plugins_url('bootstrap/css/bootstrap.min.css', __FILE__));
wp_register_style('bootstrap-flagstrap', plugins_url('bootstrap/flagstrap/css/flags.css', __FILE__));
wp_register_style('select-select', plugins_url('bootstrap/select/css/bootstrap-select.min.css', __FILE__));
wp_register_style('select-icon', plugins_url('bootstrap/select-icon/css/lib/control/iconselect.css', __FILE__));
wp_enqueue_style('rrw-style');

wp_enqueue_style('bootstrap');
wp_enqueue_style('bootstrap-flagstrap');
wp_enqueue_style('select-icon');
wp_enqueue_style('select-select');


wp_register_script( 'rrw-script', plugins_url( 'js/common.js', __FILE__ ), "jquery" );
wp_register_script( 'bootstrap', plugins_url( 'bootstrap/js/bootstrap.min.js', __FILE__ ), "jquery" );
wp_register_script( 'bootstrap-select', plugins_url( 'bootstrap/select/js/bootstrap-select.min.js', __FILE__ ), "jquery" );
wp_register_script( 'bootstrap-flagstrap', plugins_url( 'bootstrap/flagstrap/js/jquery.flagstrap.min.js', __FILE__ ), "jquery" );

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'bootstrap' );
wp_enqueue_script( 'bootstrap-flagstrap' );
wp_enqueue_script( 'bootstrap-select' );


wp_enqueue_script( 'rrw-script' );

}
if($_REQUEST['page'] == "remitradar-widgets-options"){
	add_action( 'admin_enqueue_scripts', 'rrw_loadMyScript' );
}


function add_rrw_setting(){
  //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

   	// Form nonce check
   	
	 wp_nonce_field( 'remitradar_widgets' );


	$hidden_field_name = 'mt_submit_hidden';
   
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' && check_admin_referer( 'remitradar_widgets' ) ) {
        // Read their posted value
    
        update_option( 'remitradar_widgets', strip_tags(json_encode($_POST[ 'remitradar_widgets'])),false);
        
		 ?>
		 
<div class="updated"><p><strong><?php _e('settings saved.', 'rrw' ); ?></strong></p></div>
<?php }



	$fields = array();
	$ta = get_option('remitradar_widgets');
	$widgets = json_decode($ta, true);
	
	if(!isset($widgets[0])){	
	$widgets[0]['id'] = 0;
	$widgets[0]['selected_type'] = 1;
	$widgets[0]['from'] = "US";
	$widgets[0]['to'] = "US";
	$widgets[0]['weight'] = 350;
	$widgets[0]['height'] = 500;
	} 

    echo '<div class="wrap">';
    echo "<h2>" . __( 'Remitradar widgets Settings', 'rrw' ) . "</h2>";
    ?>
    
    
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'remitradar_widgets' ) ?>">


<table class="wp-list-table widefat fixed striped posts">
	<thead>
	<tr>
		<th scope="col" id="title" class="manage-column column-date">
			<span><?_e("ID")?></span></th>
		<th scope="col" id="title" class="manage-column column-type">
			<span><?_e("Type")?></span></th>
		<th scope="col" class="manage-column column-from">
			<span><?_e("From")?></span></th>
			<th scope="col" class="manage-column column-from">
			<span><?_e("To")?></span></th>
			
		<th scope="col" class="manage-column column-size">
			<span><?_e("Size")?></span></th>
			
		<th scope="col" id="shortcode" class="manage-column column-shortcode">
			<span><?_e("Shortcode")?></span></th>
		<th scope="col" class="manage-column column-date">
			<span><?_e("Delete")?></span></th>
	</tr>
	</thead>

	<tbody id="the-list" data-wp-lists="list:post">
		<?php $row = 0; ?>
		<?php foreach($widgets as $item){ ?>
		<tr data-row="<?=$row?>" id="col-row-<?=$row?>">
		<input type="hidden" name="remitradar_widgets[<?=$row?>][id]" value="<?=$item['id']?>">
		<td class="title column-title top-center" >
				<p><?=$item['id']?></p>
			</td>
			<td class="title column-type">
				<div class="icon-select">
					<div class="selected-box">
						<div class="selected-icon">
							<img src="<?=plugins_url( 'image/type-'.$item['selected_type'].'.png', __FILE__ )?>" width="64" height="64">
						</div>
							<div class="component-icon"><img src="<?=plugins_url( 'image/arrow.png', __FILE__ )?>"></div>
							<div class="box my-icon-select-box  hide" style="overflow: hidden;">
								<div style="display: block; transition-property: transform; transform-origin: 0px 0px 0px; transform: translate(0px, 0px) translateZ(0px);">
									<div class="icon <?php if($item['selected_type'] == 1){ ?>selected<?php } ?>"><img src="<?=plugins_url( 'image/type-1.png', __FILE__ )?>" icon-value="1"></div>
									<div class="icon <?php if($item['selected_type'] == 2){ ?>selected<?php } ?>"><img src="<?=plugins_url( 'image/type-2.png', __FILE__ )?>" icon-value="2"></div>
									<div class="icon <?php if($item['selected_type'] == 3){ ?>selected<?php } ?>"><img src="<?=plugins_url( 'image/type-3.png', __FILE__ )?>" icon-value="3"></div>
									<?php if(false){ ?>
									<div class="icon <?php if($item['selected_type'] == 4){ ?>selected<?php } ?>"><img src="<?=plugins_url( 'image/type-4.png', __FILE__ )?>" icon-value="4"></div>
									<?php } ?>
								</div>
							</div>
					</div>
					<input type="hidden" id="selected-type-<?=$row?>" name="remitradar_widgets[<?=$row?>][selected_type]" value="<?=$item['selected_type']?>">
				</div>
			</td>
			
			<td class="author column-author top-center">
				<div class="form-group to_add_from">

					<select class="selectpicker" data-live-search="true"  name="remitradar_widgets[<?=$row?>][from]">
					<?php if($item['selected_type'] == "3"){ $selects = rrw_select_currency(); } else { $selects = rrw_select_country(); };  ?>
						<?php foreach($selects as $k=> $it){ ?>
							 <option <?php if($k==$item['from']){ ?>selected=""<?php } ?> value="<?=$k?>"><?=$it?></option>
						<?php } ?>
					</select>
					
				</div>
				
			</td>
			
			<td class="title column-title top-center" >
				<div class="form-group to_add_to">
					
					<select class="selectpicker" data-live-search="true"  name="remitradar_widgets[<?=$row?>][to]">
						<?php foreach($selects as $k=> $it){ ?>
							 <option <?php if($k==$item['to']){ ?>selected=""<?php } ?> value="<?=$k?>"><?=$it?></option>
						<?php } ?>
					</select>

				</div>
				
			</td>
			
			<td class="author column-size">
			<div class="form-group">
				<div class="row">
					<label class="col-sm-5 control-label"><?_e("Weight")?> (px):</label>
					<div class="col-sm-7">
						<input name="remitradar_widgets[<?=$row?>][weight]" value="<?=$item['weight']?>" type="number" class="form-control weight_add"/> 
					</div>
                </div>
                <div class="row">
					<label class="col-sm-5 control-label"><?_e("Height")?> (px):</label>
					<div class="col-sm-7">
						<input name="remitradar_widgets[<?=$row?>][height]" value="<?=$item['height']?>" type="number" class="form-control height_add"/>
					</div>
                </div>
            </div></td>
           
			<td class="shortcode column-shortcode top-center">
				<span class="shortcode">
					<input type="text" onfocus="this.select();" readonly="readonly" value="[remitradar-widgets id=&quot;<?=$item['id']?>&quot;]" class="large-text code">
					</span></td>
					
			<td class="title column-title top-center" >
				<button type="button" onclick="jQuery('#col-row-<?=$row?>').remove();" class="btn btn-danger"><?_e("Delete")?></button>
			</td>
		</tr>
		<?php $row++; ?>
		<?php } ?>
		
	</tbody>

	<tfoot>
			<tr>
				<td colspan="6"></td>
				<td class="title column-title" >
					<button type="button" onclick="add_element()" class="btn btn-success"><?_e("Add")?></button>
				</td>
			</tr>
	</tfoot>

</table>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" /></p>


</form>
</div>


  <script>

	jQuery( window ).load(function() {

		add_icon();
		
	}); 		  
		var html_1 = "";
		<?php $selects = rrw_select_country(); ?>
			<?php foreach($selects as $k=> $it){ ?>
		html_1 += "<option <?php if($k=="US"){ ?>selected=''<?php } ?> value='<?=$k?>'><?=$it?></option>";
			<?php } ?>
		
		document.option_country = html_1;

		var html_2 = "";
		<?php $selects = rrw_select_currency(); ?>
			<?php foreach($selects as $k=> $it){ ?>
		html_2 += "<option <?php if($k=="US"){ ?>selected=''<?php } ?> value='<?=$k?>'><?=$it?></option>";
			<?php } ?>
		
		document.option_currency = html_2;
 

	function add_icon(){

		jQuery('.selected-box').click(function(){
		
			jQuery(this).find(".my-icon-select-box").toggleClass("hide");

		 
		});

		jQuery('.my-icon-select-box .icon').click(function(){
			var type = jQuery(this).find("img").attr("icon-value");
			var option = "";
			var weight_add = "";
			var height_add = "";
			if(type == "3"){
				option = document.option_currency;
				weight_add = "350";
				height_add = "570";
			} if(type == "1"){
				option = document.option_country;
				weight_add = "350";
				height_add = "500";
			} if(type == "2"){
				option = document.option_country;
				weight_add = "350";
				height_add = "600";
			} if(type == "4"){
				option = document.option_country;
				weight_add = "920";
				height_add = "125";
			}
			
			var row = jQuery(this).parent().parent().parent().parent().parent().parent().attr("data-row");
			jQuery(this).parent().find(".icon").removeClass("selected");
			jQuery(this).addClass("selected");
			jQuery(this).parent().parent().parent().parent().find("input").attr("value",type);
			jQuery(this).parent().parent().parent().parent().find(".selected-icon img").attr("src",jQuery(this).find("img").attr("src"));

			var html  = '<select class="selectpicker to_add" data-live-search="true"  name="remitradar_widgets['+row+'][from]">';
			html += option;
			html += "</select>";
			jQuery(this).parent().parent().parent().parent().parent().parent().find(".to_add_from").html(html);

			var html2  = '<select class="selectpicker to_add" data-live-search="true"  name="remitradar_widgets['+row+'][to]">';
			html2 += option;
			html2 += "</select>";
			jQuery(this).parent().parent().parent().parent().parent().parent().find(".to_add_to").html(html2);
			
			jQuery(this).parent().parent().parent().parent().parent().parent().find(".height_add").attr("value",height_add);
			jQuery(this).parent().parent().parent().parent().parent().parent().find(".weight_add").attr("value",weight_add);
			
			
			jQuery('.selectpicker').selectpicker({});
		 
		});

		}
	function add_element(){
		var row = <?=$row?>;
		var row_id = <?=($widgets[(count($widgets)-1)]['id']+1)?>;

		var html = '';
		
		html += '<td class="title column-title top-center" ><p>'+row_id+'</p></td>';
		html += '<tr data-row="'+row+'" id="col-row-'+row+'"><input type="hidden"  name="remitradar_widgets['+row+'][id]" value="'+row_id+'">';
		
		html += '<td class="title column-type"><div class="icon-select"><div class="selected-box"><div class="selected-icon"><img src="<?=plugins_url( 'image/type-1.png', __FILE__ )?>" width="64" height="64"></div><div class="component-icon"><img src="<?=plugins_url( 'image/arrow.png', __FILE__ )?>"></div><div class="box my-icon-select-box hide" style="overflow: hidden;"><div style="display: block; transition-property: transform; transform-origin: 0px 0px 0px; transform: translate(0px, 0px) translateZ(0px);"><div class="icon selected"><img src="<?=plugins_url( 'image/type-1.png', __FILE__ )?>" icon-value="1" width="240" height="240"></div><div class="icon"><img src="<?=plugins_url( 'image/type-2.png', __FILE__ )?>" icon-value="2" width="240" height="240"></div><div class="icon"><img src="<?=plugins_url( 'image/type-3.png', __FILE__ )?>" icon-value="3" width="240" height="240"></div><div class="icon"><img src="<?=plugins_url( 'image/type-4.png', __FILE__ )?>" icon-value="4" width="240" height="240"></div></div></div></div><input type="hidden" id="selected-type-'+row+'" name="remitradar_widgets['+row+'][selected_type]" value="1"></div></td>';


					
		html += '<td class="author column-author top-center"><div class="form-group"><select class="selectpicker to_add" data-live-search="true"  name="remitradar_widgets['+row+'][from]">';
		
		html +=	document.option_country;
		
		html += '</select></div></td>';

		html += '<td class="author column-author top-center"><div class="form-group"><select class="selectpicker to_add" data-live-search="true"  name="remitradar_widgets['+row+'][to]">';

		html +=	document.option_country;
		
		html += '</select></div></td>';
					
		html += '<td class="author column-size">';
		html += '<div class="form-group"><div class="row"><label class="col-sm-5 control-label"><?_e("Weight")?> (px):</label><div class="col-sm-7"><input name="remitradar_widgets['+row+'][weight]" value="350" type="number" class="form-control"/></div></div>';
		html += '<div class="row"><label class="col-sm-5 control-label"><?_e("Height")?> (px):</label><div class="col-sm-7"><input name="remitradar_widgets['+row+'][height]" value="500" type="number" class="form-control"/></div></div></div></td>';

		html += '<td class="shortcode column-shortcode top-center"><span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="[remitradar-widgets id=&quot;'+row_id+'&quot;]" class="large-text code"></span></td>';
		html += '<td class="title column-title top-center" ><button onclick="jQuery(\'#col-row-'+row+'\').remove();" type="button" class="btn btn-danger"><?_e("Delete")?></button></td>';
		html += '</tr>';


		jQuery('#the-list').append(html);
		


		row++;
		row_id++;

		jQuery('form').submit();

	}
        
            
  </script>

<?php
 
}


// Register a new shortcode: [vk_login_registration]
add_shortcode('remitradar-widgets', 'rrw_registration_shortcode');

// The callback function that will replace [book]

function rrw_registration_shortcode($attr) {

	if(!isset($attr['id'])){ return false; }

	$fields = array();
	$ta = get_option('remitradar_widgets');
	$widgets = json_decode($ta, true);

	foreach($widgets as $item){
		if($item["id"]==$attr['id']){ break; }
	}
	
    ob_start();

    $lang = '';

    $locale = get_bloginfo( 'language' );

	if(($locale == 'ru-RU') || ($locale == 'uk')){
		$lang = 'ru.';
	}

	if($item['selected_type'] == '1'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements';
	} elseif($item['selected_type'] == '2'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements/Companies';
	} elseif($item['selected_type'] == '3'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements/ExchangeRates';
	} elseif($item['selected_type'] == '4'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements/Panel';
	} else {
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements';
	}

	$weight = ($item['weight'] != "") ? $item['weight'] : "350";
	$height = ($item['height'] != "") ? $item['height'] : "500";
	
	?>
	
	<iframe style="border:none" width="<?=$weight?>" height="<?=$height?>" src="<?=$url?>?from=<?=$item['from']?>&to=<?=$item['to']?>&partner=webmasters"></iframe>

   <?php return ob_get_clean();
}

function rrw_select_country() {

	return array(
	"AB" => "Abkhazia",
	"AD" => "Andorra",
	"AE" => "UAE",
	"AF" => "Afghanistan",
	"AG" => "Antigua and Barbuda",
	"AI" => "Anguilla",
	"AL" => "Albania",
	"AM" => "Armenia",
	"AN" => "Netherlands Antilles",
	"AO" => "Angola",
	"AR" => "Argentina",
	"AS" => "American Samoa",
	"AT" => "Austria",
	"AU" => "Australia",
	"AW" => "Aruba",
	"AX" => "Aland Islands",
	"AZ" => "Azerbaijan",
	"BA" => "Bosnia and Herzegovina",
	"BB" => "Barbados",
	"BD" => "Bangladesh",
	"BE" => "Belgium",
	"BF" => "Burkina Faso",
	"BG" => "Bulgaria",
	"BH" => "Bahrain",
	"BI" => "Burundi",
	"BJ" => "Benin",
	"BM" => "Bermuda",
	"BN" => "Brunei Darussalam",
	"BO" => "Bolivia",
	"BQ" => "Bonaire, Sint Eustatius and Saba",
	"BR" => "Brazil",
	"BS" => "Bahamas",
	"BT" => "Bhutan",
	"BV" => "Bouvet Island",
	"BW" => "Botswana",
	"BY" => "Belarus",
	"BZ" => "Belize",
	"CA" => "Canada",
	"CC" => "Cocos Islands",
	"CD" => "Congo",
	"CF" => "Central African Republic",
	"CG" => "Congo",
	"CH" => "Switzerland",
	"CI" => "Cote d'Ivoire",
	"CK" => "Cook Islands",
	"CL" => "Chile",
	"CM" => "Cameroon",
	"CN" => "China",
	"CO" => "Colombia",
	"CR" => "Costa Rica",
	"CU" => "Cuba",
	"CV" => "Cape Verde",
	"CW" => "Curaçao",
	"CX" => "Christmas Island",
	"CY" => "Cyprus",
	"CZ" => "Czech Republic",
	"DE" => "Germany",
	"DJ" => "Djibouti",
	"DK" => "Denmark",
	"DM" => "Dominica",
	"DO" => "Dominican Republic",
	"DZ" => "Algeria",
	"EC" => "Ecuador",
	"EE" => "Estonia",
	"EG" => "Egypt",
	"EH" => "Western Sahara",
	"ER" => "Eritrea",
	"ES" => "Spain",
	"ET" => "Ethiopia",
	"FI" => "Finland",
	"FJ" => "Fiji",
	"FK" => "Falkland Islands (Malvinas)",
	"FM" => "Micronesia",
	"FO" => "Faroe Islands",
	"FR" => "France",
	"GA" => "Gabon",
	"GB" => "United Kingdom",
	"GD" => "Grenada",
	"GE" => "Georgia",
	"GF" => "French Guiana",
	"GG" => "Guernsey",
	"GH" => "Ghana",
	"GI" => "Gibraltar",
	"GL" => "Greenland",
	"GM" => "Gambia",
	"GN" => "Guinea",
	"GP" => "Guadeloupe",
	"GQ" => "Equatorial Guinea",
	"GR" => "Greece",
	"GS" => "South Georgia",
	"GT" => "Guatemala",
	"GU" => "Guam",
	"GW" => "Guinea-Bissau",
	"GY" => "Guyana",
	"HK" => "Hong Kong",
	"HM" => "Heard Island / McDonald",
	"HN" => "Honduras",
	"HR" => "Croatia",
	"HT" => "Haiti",
	"HU" => "Hungary",
	"ID" => "Indonesia",
	"IE" => "Ireland",
	"IL" => "Israel",
	"IM" => "Isle of Man",
	"IN" => "India",
	"IO" => "B.I.O.T.",
	"IQ" => "Iraq",
	"IR" => "Iran",
	"IS" => "Iceland",
	"IT" => "Italy",
	"JE" => "Jersey",
	"JM" => "Jamaica",
	"JO" => "Jordan",
	"JP" => "Japan",
	"KE" => "Kenya",
	"KG" => "Kyrgyzstan",
	"KH" => "Cambodia",
	"KI" => "Kiribati",
	"KM" => "Comoros",
	"KN" => "Saint Kitts and Nevis",
	"KP" => "Korea, Democratic People's Republic of",
	"KR" => "Korea",
	"KW" => "Kuwait",
	"KY" => "Cayman Islands",
	"KZ" => "Kazakhstan",
	"LA" => "Lao People's D.R.",
	"LB" => "Lebanon",
	"LC" => "Saint Lucia",
	"LI" => "Liechtenstein",
	"LK" => "Sri Lanka",
	"LR" => "Liberia",
	"LS" => "Lesotho",
	"LT" => "Lithuania",
	"LU" => "Luxembourg",
	"LV" => "Latvia",
	"LY" => "Libyan Arab Jamahiriya",
	"MA" => "Morocco",
	"MC" => "Monaco",
	"MD" => "Moldova",
	"ME" => "Montenegro",
	"MG" => "Madagascar",
	"MH" => "Marshall Islands",
	"MK" => "Macedonia",
	"ML" => "Mali",
	"MM" => "Myanmar",
	"MN" => "Mongolia",
	"MO" => "Macao",
	"MP" => "Northern Mariana Is.",
	"MQ" => "Martinique",
	"MR" => "Mauritania",
	"MS" => "Montserrat",
	"MT" => "Malta",
	"MU" => "Mauritius",
	"MV" => "Maldives",
	"MW" => "Malawi",
	"MX" => "Mexico",
	"MY" => "Malaysia",
	"MZ" => "Mozambique",
	"NA" => "Namibia",
	"NC" => "New Caledonia",
	"NE" => "Niger",
	"NF" => "Norfolk Island",
	"NG" => "Nigeria",
	"NI" => "Nicaragua",
	"NL" => "Netherlands",
	"NO" => "Norway",
	"NP" => "Nepal",
	"NR" => "Nauru",
	"NU" => "Niue",
	"NZ" => "New Zealand",
	"OM" => "Oman",
	"OS" => "South Ossetia",
	"PA" => "Panama",
	"PE" => "Peru",
	"PF" => "French Polynesia",
	"PG" => "Papua New Guinea",
	"PH" => "Philippines",
	"PK" => "Pakistan",
	"PL" => "Poland",
	"PM" => "Saint Pierre & Miquelon",
	"PN" => "Pitcairn",
	"PR" => "Puerto Rico",
	"PS" => "Palestinian Territory",
	"PT" => "Portugal",
	"PW" => "Palau",
	"PY" => "Paraguay",
	"QA" => "Qatar",
	"RE" => "Reunion",
	"RO" => "Romania",
	"RS" => "Serbia",
	"RU" => "Russia",
	"RW" => "Rwanda",
	"SA" => "Saudi Arabia",
	"SB" => "Solomon Islands",
	"SC" => "Seychelles",
	"SD" => "Sudan",
	"SE" => "Sweden",
	"SG" => "Singapore",
	"SH" => "Saint Helena",
	"SI" => "Slovenia",
	"SJ" => "Svalbard",
	"SK" => "Slovakia",
	"SL" => "Sierra Leone",
	"SM" => "San Marino",
	"SN" => "Senegal",
	"SO" => "Somalia",
	"SR" => "Suriname",
	"SS" => "South Sudan",
	"ST" => "Sao Tome and Principe",
	"SV" => "El Salvador",
	"SX" => "Sint Maarten",
	"SY" => "Syrian Arab Republic",
	"SZ" => "Swaziland",
	"TC" => "Turks & Caicos Islands",
	"TD" => "Chad",
	"TF" => "French S.T.",
	"TG" => "Togo",
	"TH" => "Thailand",
	"TJ" => "Tajikistan",
	"TK" => "Tokelau",
	"TL" => "Timor-Leste",
	"TM" => "Turkmenistan",
	"TN" => "Tunisia",
	"TO" => "Tonga",
	"TR" => "Turkey",
	"TT" => "Trinidad and Tobago",
	"TV" => "Tuvalu",
	"TW" => "Taiwan",
	"TZ" => "Tanzania",
	"UA" => "Ukraine",
	"UG" => "Uganda",
	"UM" => "U.S. M.O. Islands",
	"US" => "United States",
	"UY" => "Uruguay",
	"UZ" => "Uzbekistan",
	"VA" => "Vatican",
	"VC" => "Saint Vincent",
	"VE" => "Venezuela",
	"VG" => "Virgin Islands, British",
	"VI" => "Virgin Islands, U.S.",
	"VN" => "Vietnam",
	"VU" => "Vanuatu",
	"WF" => "Wallis and Futuna",
	"WS" => "Samoa",
	"XK" => "Kosovo",
	"YE" => "Yemen",
	"YT" => "Mayotte",
	"ZA" => "South Africa",
	"ZM" => "Zambia",
	"ZW" => "Zimbabwe"
	);


}

function rrw_select_currency() {

	return array(
	"ADP" => "ADP - Andorran Peseta",
	"AED" => "AED - United Arab Emirates Dirham",
	"AFA" => "AFA - Afghanistan Afghani",
	"AFN" => "AFN - Afghan Afghani",
	"ALL" => "ALL - Albanian Lek",
	"AMD" => "AMD - Armenian Dram",
	"ANG" => "ANG - Netherlands Antillian Guilder",
	"AOA" => "AOA - Angolan Kwanza",
	"AOK" => "AOK - Angolan Kwanza",
	"ARS" => "ARS - Argentine Peso",
	"AUD" => "AUD - Australian Dollar",
	"AWG" => "AWG - Aruban Florin",
	"AZN" => "AZN - Azerbaijani Manat",
	"BAM" => "BAM - Bosnia-Herzegovina",
	"BBD" => "BBD - Barbados Dollar",
	"BDT" => "BDT - Bangladeshi Taka",
	"BGN" => "BGN - Bulgarian Lev",
	"BHD" => "BHD - Bahraini Dinar",
	"BIF" => "BIF - Burundi Franc",
	"BMD" => "BMD - Bermudian Dollar",
	"BND" => "BND - Brunei Dollar",
	"BOB" => "BOB - Bolivian Boliviano",
	"BRL" => "BRL - Brazilian Real",
	"BSD" => "BSD - Bahamian Dollar",
	"BTN" => "BTN - Bhutan Ngultrum",
	"BUK" => "BUK - Burma Kyat",
	"BWP" => "BWP - Botswanian Pula",
	"BYR" => "BYR - Belarusian Ruble",
	"BZD" => "BZD - Belize Dollar",
	"CAD" => "CAD - Canadian Dollar",
	"CDF" => "CDF - Congolese Franc",
	"CHF" => "CHF - Swiss Franc",
	"CLF" => "CLF - Chilean Unidades de Fomento",
	"CLP" => "CLP - Chilean Peso",
	"CNY" => "CNY - Yuan (Chinese) Renminbi",
	"COP" => "COP - Colombian Peso",
	"CRC" => "CRC - Costa Rican Colon",
	"CUP" => "CUP - Cuban Peso",
	"CVE" => "CVE - Cape Verde Escudo",
	"CYP" => "CYP - Cyprus Pound",
	"CZK" => "CZK - Czech Republic Koruna",
	"DJF" => "DJF - Djiboutian Franc",
	"DKK" => "DKK - Danish Krone",
	"DOP" => "DOP - Dominican Peso",
	"DZD" => "DZD - Algerian Dinar",
	"ECS" => "ECS - Ecuador Sucre",
	"EEK" => "EEK - Estonian Kroon",
	"EGP" => "EGP - Egyptian Pound",
	"ERN" => "ERN - Eritrean Nakfa",
	"ETB" => "ETB - Ethiopian Birr",
	"EUR" => "EUR - Euro",
	"FJD" => "FJD - Fiji Dollar",
	"FKP" => "FKP - Falkland Islands Pound",
	"GBP" => "GBP - British Pound",
	"GEL" => "GEL - Georgian Lari",
	"GHC" => "GHC - Ghanaian Cedi",
	"GHS" => "GHS - Ghanaian Cedi",
	"GIP" => "GIP - Gibraltar Pound",
	"GMD" => "GMD - Gambian Dalasi",
	"GNF" => "GNF - Guinea Franc",
	"GTQ" => "GTQ - Guatemalan Quetzal",
	"GWP" => "GWP - Guinea-Bissau Peso",
	"GYD" => "GYD - Guyanan Dollar",
	"HKD" => "HKD - Hong Kong Dollar",
	"HNL" => "HNL - Honduran Lempira",
	"HRK" => "HRK - Croatian Kuna",
	"HTG" => "HTG - Haitian Gourde",
	"HUF" => "HUF - Hungarian Forint",
	"IDR" => "IDR - Indonesian Rupiah",
	"IEP" => "IEP - Irish Punt",
	"ILS" => "ILS - Israeli Shekel",
	"INR" => "INR - Indian Rupee",
	"IQD" => "IQD - Iraqi Dinar",
	"IRR" => "IRR - Iranian Rial",
	"ISK" => "ISK - Icelandic Króna",
	"JMD" => "JMD - Jamaican Dollar",
	"JOD" => "JOD - Jordanian Dinar",
	"JPY" => "JPY - Japanese Yen",
	"KES" => "KES - Kenyan Schilling",
	"KGS" => "KGS - Kyrgystani Som",
	"KHR" => "KHR - Kampuchean (Cambodian) Riel",
	"KMF" => "KMF - Comoros Franc",
	"KPW" => "KPW - North Korean Won",
	"KRW" => "KRW - South Korean Won",
	"KWD" => "KWD - Kuwaiti Dinar",
	"KYD" => "KYD - Cayman Islands Dollar",
	"KZT" => "KZT - Kazakhstani Tenge",
	"LAK" => "LAK - Lao Kip",
	"LBP" => "LBP - Lebanese Pound",
	"LKR" => "LKR - Sri Lanka Rupee",
	"LRD" => "LRD - Liberian Dollar",
	"LSL" => "LSL - Lesotho Loti",
	"LYD" => "LYD - Libyan Dinar",
	"MAD" => "MAD - Moroccan Dirham",
	"MDL" => "MDL - Moldovan Leu",
	"MGA" => "MGA - Malagasy Ariary",
	"MGF" => "MGF - Malagasy Franc",
	"MKD" => "MKD - Macedonian denar",
	"MMK" => "MMK - Myanmar Kyat",
	"MNT" => "MNT - Mongolian Tugrik",
	"MOP" => "MOP - Macau Pataca",
	"MRO" => "MRO - Mauritanian Ouguiya",
	"MTL" => "MTL - Maltese Lira",
	"MUR" => "MUR - Mauritius Rupee",
	"MVR" => "MVR - Maldive Rufiyaa",
	"MWK" => "MWK - Malawi Kwacha",
	"MXN" => "MXN - Mexican Peso",
	"MXP" => "MXP - Mexican Peso",
	"MYR" => "MYR - Malaysian Ringgit",
	"MZM" => "MZM - Mozambique Metical",
	"MZN" => "MZN - Mozambican Metical",
	"NAD" => "NAD - Namibian Dollar",
	"NGN" => "NGN - Nigerian Naira",
	"NIO" => "NIO - Nicaraguan Cordoba",
	"NOK" => "NOK - Norwegian Kroner",
	"NPR" => "NPR - Nepalese Rupee",
	"NZD" => "NZD - New Zealand Dollar",
	"OMR" => "OMR - Omani Rial",
	"PAB" => "PAB - Panamanian Balboa",
	"PEN" => "PEN - Peruvian Nuevo Sol",
	"PGK" => "PGK - Papua New Guinea Kina",
	"PHP" => "PHP - Philippine Peso",
	"PKR" => "PKR - Pakistan Rupee",
	"PLN" => "PLN - Polish Zloty",
	"PYG" => "PYG - Paraguay Guarani",
	"QAR" => "QAR - Qatari Rial",
	"RON" => "RON - Romanian Leu",
	"RSD" => "RSD - Serbian Dinar",
	"RUB" => "RUB - Russian Ruble",
	"RWF" => "RWF - Rwanda Franc",
	"SAR" => "SAR - Saudi Arabian Riyal",
	"SBD" => "SBD - Solomon Islands Dollar",
	"SCR" => "SCR - Seychelles Rupee",
	"SDG" => "SDG - Sudanese Pound",
	"SDP" => "SDP - Sudanese Pound",
	"SEK" => "SEK - Swedish Krona",
	"SGD" => "SGD - Singapore Dollar",
	"SHP" => "SHP - St. Helena Pound",
	"SKK" => "SKK - Slovak Koruna",
	"SLL" => "SLL - Sierra Leone Leone",
	"SOS" => "SOS - Somali Schilling",
	"SRD" => "SRD - Surinamese Dollar",
	"SRG" => "SRG - Suriname Guilder",
	"STD" => "STD - Sao Tome and Principe Dobra",
	"SVC" => "SVC - El Salvador Colon",
	"SYP" => "SYP - Syrian Potmd",
	"SZL" => "SZL - Swaziland Lilangeni",
	"THB" => "THB - Thai Baht",
	"TJS" => "TJS - Tajikistani Somoni",
	"TMT" => "TMT - Turkmenistani Manat",
	"TND" => "TND - Tunisian Dinar",
	"TOP" => "TOP - Tongan Paanga",
	"TPE" => "TPE - East Timor Escudo",
	"TRY" => "TRY - Turkish Lira",
	"TTD" => "TTD - Trinidad and Tobago Dollar",
	"TWD" => "TWD - Taiwan Dollar",
	"TZS" => "TZS - Tanzanian Schilling",
	"UAH" => "UAH - Ukrainian Hryvnia",
	"UGX" => "UGX - Uganda Shilling",
	"USD" => "USD - US Dollar",
	"UYU" => "UYU - Uruguayan Peso",
	"UZS" => "UZS - Uzbekistani Som",
	"VEF" => "VEF - Venezualan Bolivar",
	"VND" => "VND - Vietnamese Dong",
	"VUV" => "VUV - Vanuatu Vatu",
	"WST" => "WST - Samoan Tala",
	"XAF" => "XAF - Central African CFA Franc BEAC",
	"XCD" => "XCD - East Caribbean Dollar",
	"XOF" => "XOF - West African CFA Franc",
	"YDD" => "YDD - Democratic Yemeni Dinar",
	"YER" => "YER - Yemeni Rial",
	"YUD" => "YUD - New Yugoslavia Dinar",
	"ZAR" => "ZAR - South African Rand",
	"ZMK" => "ZMK - Zambian Kwacha",
	"ZRZ" => "ZRZ - Zaire Zaire",
	"ZWD" => "ZWD - Zimbabwe Dollar",
	"ZWL" => "ZWL - Fourth Zimbabwean Dollar"
	);

}





class RemitRadar_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'RemitRadar_Widget', // ID 
			'RemitRadar Widget',
			array( 'description' => __( 'RemitRadar Widget' ), )
		);
	}

	// 2)	Сохранение массива настроек
	public function update( $new_instance, $old_instance ) {
		$instance = array(); 
		$instance['cat'] = strip_tags( $new_instance['cat'] ); 
		return $instance;
	}
	

	public function form( $instance ) {
?>

		<?php $categories = get_categories(); ?>
		<p>
			Add widgets <a href="admin.php?page=remitradar-widgets-options">here</a> 
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">Widget ID</label>		
			<select name="<?php echo $this->get_field_name('cat'); ?>" id="<?php echo $this->get_field_id('cat'); ?>">
			<?php
				$ta = get_option('remitradar_widgets');
				$widgets = json_decode($ta, true);

				foreach($widgets as $item){
					
					echo '<option value="' . intval( $item["id"] ) . '"'
						. selected( $instance['cat'], $item["id"], false )
						. '>' . $item["id"] . "</option>\n";
				}
			?>
			</select>
		</p>
<?php
	}
	

	public function widget( $args, $instance ) {

	if(!isset($instance['cat'])){ return false; }

	$fields = array();
	$ta = get_option('remitradar_widgets');
	$widgets = json_decode($ta, true);

	foreach($widgets as $item){
		if($item["id"]==$instance['cat']){ break; }
	}
	
    ob_start();
    
    $lang = '';

    $locale = get_bloginfo( 'language' );

	if(($locale == 'ru-RU') || ($locale == 'uk')){
		$lang = 'ru.';
	}

	if($item['selected_type'] == '1'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements';
	} elseif($item['selected_type'] == '2'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements/Companies';
	} elseif($item['selected_type'] == '3'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements/ExchangeRates';
	} elseif($item['selected_type'] == '4'){
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements/Panel';
	} else {
		
		$url = 'https://'.$lang.'remitradar.com/SharedElements';
	}

	$weight = ($item['weight'] != "") ? $item['weight'] : "350";
	$height = ($item['height'] != "") ? $item['height'] : "500";
	
	?>
	<div>
	<iframe style="border:none" width="<?=$weight?>" height="<?=$height?>" src="<?=$url?>?from=<?=$item['from']?>&to=<?=$item['to']?>&partner=webmasters"></iframe>
	</div>
   <?php echo ob_get_clean();

	}
}


