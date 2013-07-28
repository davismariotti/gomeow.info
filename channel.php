<?php
	include("functions.php");
	$dates = array();
	function makeBigArray($values) {
		$times = array();
		foreach ($values as $value) {
			$year = substr($value, 0, 4);
			$month = substr($value, 4, 2);
			$day = substr($value, 6, 7);
			$times[$year][$month][$day] = "#" . $_GET['channel'] . "_" . $value . ".log";
		}
		return $times;
	}
	function startsWith($haystack, $needle){
		return !strncmp($haystack, $needle, strlen($needle));
	}
	if ($handle = opendir('../../home/znc/.znc/users/Billy/moddata/log')) {
		$x = 0;
		while (false !== ($entry = readdir($handle))) {
			if(startsWith($entry, "#" . $_GET['channel']) == false) {
				continue;
			}
			if(in_array(substr($entry, -12, -4), $dates) == false) {
				$dates[$x] = substr($entry, -12, -4);
			}
			$x = $x + 1;
		}
		closedir($handle);
	}
	$bigArray = makeBigArray($dates);
?>
<html>
	<head>
		<title>Channel: #<?php echo $_GET['channel']; ?></title>
		<?php headIncludes(); ?>
	</head>
	<body onload="loadLink();">
		<script type="text/javascript">

			function jumpToBottom() {
				var urllocation = location.href;
				if(urllocation.indexOf("#b") > -1){
					window.location.hash="b";
				} else {
				return false;
				}
			}

			var dates = <?php echo(json_encode(array_reverse($bigArray, true))); ?>;

			function updateMonth() {
				var year = document.getElementById("year").options[document.getElementById("year").selectedIndex].text;
				var months = dates[year].sort();
				var string = "";
				var checked = false;
				var x = 0;
				for(var month in months) {
					x = x + 1;
					var days = months[month].sort();
					string = string + "<option>" + month + "</option>";
					var dayString = "";
					if(x == 1) {
						for(var day in days) {
							dayString = "<option>" + day + "</option>" + dayString;
						}
						document.getElementById("day").innerHTML = dayString;
					}
				}
				document.getElementById("month").innerHTML = string;
				loadLink();
			}

			function updateDay() {
				var year = document.getElementById("year").options[document.getElementById("year").selectedIndex].text;
				var month = document.getElementById("month").options[document.getElementById("month").selectedIndex].text;
				var days = dates[year][month].sort();
				var dayString = "";
				for(var day in days) {
					dayString = "<option>" + day + "</option>" + dayString;
				}
				document.getElementById("day").innerHTML = dayString;
				loadLink();
			}

			function loadLink() {
				var year = document.getElementById("year").options[document.getElementById("year").selectedIndex].text;
				var month = document.getElementById("month").options[document.getElementById("month").selectedIndex].text;
				var day = document.getElementById("day").options[document.getElementById("day").selectedIndex].text;
				$("#link").load("log.php?file=" + "<?php echo $_GET['channel']; ?>" + "_" + year + month + day);

				jumpToBottom();
			}
		</script>
		<?php navBar(); ?>
		<div id="mainLog" class="container">
			<div class="well mainContent">
				<form>
					<fieldset>
						<table>
							<tr style="text-align: center;">
								<td>Year</td>
								<td>Month</td>
								<td>Day</td>
							<tr>
								<td>
									<select id="year" onchange="updateMonth();">
										<?php
											asort($bigArray);
											$bigArray = array_reverse($bigArray, true);
											foreach($bigArray as $key => $value) {
												?><option value="<?php echo $key; ?>"><?php echo $key; ?></option><?php
											}
										?>
									</select>
								</td>
								<td>
									<select id="month" onchange="updateDay();">
										<?php
											$first = "";
											asort($bigArray);
											$bigArray = array_reverse($bigArray, true);
											foreach ($bigArray as $key => $value) {
												$first = $key;
												break;
											}
											$array = $bigArray[$first];
											asort($array);
											$array = array_reverse($array, true);
											foreach($array as $key => $value) {
												?><option value="<?php echo $key; ?>"><?php echo $key; ?></option><?php
											}
										?>
									</select>
								</td>
								<td>
									<select id="day" onchange="loadLink();">
										<?php
											$first = "";
											asort($bigArray);
											$bigArray = array_reverse($bigArray, true);
											foreach ($bigArray as $key => $value) {
												$first = $key;
												break;
											}
											$array = $bigArray[$first];
											asort($array);
											$array = array_reverse($array, true);
											foreach ($array as $key => $value) {
												$first = $key;
												break;
											}
											$dayArray = $array[$first];
											asort($dayArray);
											$dayArray = array_reverse($dayArray, true);
											foreach($dayArray as $key => $value) {
												?><option value="<?php echo $key; ?>"><?php echo $key; ?></option><?php
											}
										?>
									</select>
								</td>
							</tr>
						</table>
					</fieldset>
					<a class="btn btn-success" href="#b">Jump to bottom</a>
				</form>
				<div id="link">
				</div>
				<a name="b"></a>
			</div>
		</div>
	</body>
</html>
