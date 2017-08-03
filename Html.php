<?php
	class HTML
	{
		private $pageTitle;
		private $progName;

		private $scripts = array();
		private $css = array();
		private $favicon_url;

		public function __construct($pageTitle) {
			$this->setPageTitle($pageTitle);
		}

		public function setPageTitle($pageTitle) {
			$this->pageTitle = $pageTitle;
		}

		public function addScript($script_path) {
			array_push($this->scripts, $script_path);
		}

		public function addCSS($css_path) {
			array_push($this->css, $css_path);
		}

		public function addFavIcon($url) {
			$this->favicon_url = $url;
		}

		public function displayHeader() {
			echo "<!DOCTYPE html>\n";
			echo "<html>\n";
			echo "<head> <title>" . $this->pageTitle . "</title>\n";

			if(sizeof($this->css) > 0) {
				for($i = 0; $i < sizeof($this->css); $i++) {
					echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->css[$i] ."\">\n";
				}
			}

			if(sizeof($this->scripts) > 0) {
				for($i = 0; $i < sizeof($this->scripts); $i++) {
					echo "<script src=\"" . $this->scripts[$i] . "\"></script>\n";
				}
			}

			if(strlen($this->favicon_url) > 0) {
				echo "<link rel=\"icon\" type=\"image/png\" href=\"$this->favicon_url\">\n";
			}

			echo "</head>\n";
			echo "<body>\n";
		}

		public function displayFooter() {
			echo "\n</body>\n";
			echo "</html>\n";
		}

		public static function getProgName($full_name) {
			$ret_name = "";
			for($i = strlen($full_name) - 1; $full_name[$i] != '/'; $i--) {
				$ret_name = $full_name[$i] . $ret_name;
			}

			return $ret_name;
		}

		public function beginForm($method, $action, $file_upload) {
			if($file_upload) {
				echo "<form action=\"" . $action . "\" method=\"" . $method . "\" enctype=\"multipart/form-data\" >\n";
			} else {
				echo "<form action=\"" . $action . "\" method=\"" . $method . "\" >\n";
			}
		}

		public function endForm() {
			echo "</form>\n";
		}

		public function beginDiv($div_name, $class_or_id) {
			printf("<div %s=\"$div_name\">\n", (strcmp($class_or_id, 'class') == 0)?'class':'id');
		}

		public function endDiv() {
			echo "</div>\n";
		}

		public function beginFieldSet($legend) {
			echo "<fieldset>\n";
			if(strlen($legend) > 0) echo "<legend> $legend </legend>";
		}

		public function endFieldSet() {
			echo "</fieldset>\n";
		}

		public function inputItem($id, $input_type, $value, $disabled) {
			printf("<input name = \"" . $id . "\" id = \"" . $id . "\" type=\"" . $input_type . "\" value=\"" . $value . "\" %s>\n", ($disabled == true)?"disabled":"");
			//echo "<input name = \"" . $id . "\" id = \"" . $id . "\" type=\"" . $input_type . "\" value=\"" . $value . "\">\n";
		}

		/*
		*	renderLogin(login_id, login_class, username_id, password_id, horizontal)
		*
		*	login_id - HTML id to use for the login div
		*	login_class - HTML class to use for the login div
		*	username_id - HTML id to use for the username textfield
		*	password_id - HTML id to use for the password field
		*	horizontal - (true/false) orientation of the password div.
		*/

		public function renderLogin($login_id, $login_class, $username_id, $password_id, $horizontal) {
			printf("<div %s%s>\n", ((strlen($login_id) > 0) ? "id=\"$login_id\" ":""), ( (strlen($login_class) > 0) ? "class=\"$login_class\"":""));
			echo "	<table>\n";
			echo "		<tr>\n";
			echo "			<td>Username: </td>\n";
			echo "			<td>\n";
			$this->inputItem($username_id, "text", "");
			echo "			</td>\n";
			if($horizontal == false) {
				echo "		</tr>\n";
				echo "		<tr>\n";
			}			
			echo "			<td>Password: </td>\n";
			echo "			<td>\n";
			$this->inputItem($password_id, "password", "");
			echo "			</td>\n";
			echo "		</tr>\n";
			if($horizontal == false) {
				echo "		</tr>\n";
				echo "		<tr>\n";
				echo "		<td></td>";
			}
			echo "	<td>\n";
			$this->inputItem("submit", "submit", "Login");
			echo "	</td>\n";
			echo "</tr>\n";	
			echo "	</table>\n";
			echo "</div>\n";
		}

	}
?>