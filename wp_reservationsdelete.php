<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "wp_reservationsinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$wp_reservations_delete = NULL; // Initialize page object first

class cwp_reservations_delete extends cwp_reservations {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{AA939E07-3D1E-49D2-B9FF-BB5F5AC80C48}";

	// Table name
	var $TableName = 'wp_reservations';

	// Page object name
	var $PageObjName = 'wp_reservations_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (wp_reservations)
		if (!isset($GLOBALS["wp_reservations"]) || get_class($GLOBALS["wp_reservations"]) == "cwp_reservations") {
			$GLOBALS["wp_reservations"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["wp_reservations"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'wp_reservations', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("wp_reservationslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->arrival->SetVisibility();
		$this->departure->SetVisibility();
		$this->user->SetVisibility();
		$this->name->SetVisibility();
		$this->_email->SetVisibility();
		$this->country->SetVisibility();
		$this->approve->SetVisibility();
		$this->room->SetVisibility();
		$this->roomnumber->SetVisibility();
		$this->number->SetVisibility();
		$this->childs->SetVisibility();
		$this->price->SetVisibility();
		$this->reservated->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $wp_reservations;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($wp_reservations);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("wp_reservationslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in wp_reservations class, wp_reservationsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("wp_reservationslist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->arrival->setDbValue($rs->fields('arrival'));
		$this->departure->setDbValue($rs->fields('departure'));
		$this->user->setDbValue($rs->fields('user'));
		$this->name->setDbValue($rs->fields('name'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->country->setDbValue($rs->fields('country'));
		$this->approve->setDbValue($rs->fields('approve'));
		$this->room->setDbValue($rs->fields('room'));
		$this->roomnumber->setDbValue($rs->fields('roomnumber'));
		$this->number->setDbValue($rs->fields('number'));
		$this->childs->setDbValue($rs->fields('childs'));
		$this->price->setDbValue($rs->fields('price'));
		$this->custom->setDbValue($rs->fields('custom'));
		$this->customp->setDbValue($rs->fields('customp'));
		$this->reservated->setDbValue($rs->fields('reservated'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->arrival->DbValue = $row['arrival'];
		$this->departure->DbValue = $row['departure'];
		$this->user->DbValue = $row['user'];
		$this->name->DbValue = $row['name'];
		$this->_email->DbValue = $row['email'];
		$this->country->DbValue = $row['country'];
		$this->approve->DbValue = $row['approve'];
		$this->room->DbValue = $row['room'];
		$this->roomnumber->DbValue = $row['roomnumber'];
		$this->number->DbValue = $row['number'];
		$this->childs->DbValue = $row['childs'];
		$this->price->DbValue = $row['price'];
		$this->custom->DbValue = $row['custom'];
		$this->customp->DbValue = $row['customp'];
		$this->reservated->DbValue = $row['reservated'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// arrival
		// departure
		// user
		// name
		// email
		// country
		// approve
		// room
		// roomnumber
		// number
		// childs
		// price
		// custom
		// customp
		// reservated

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// arrival
		$this->arrival->ViewValue = $this->arrival->CurrentValue;
		$this->arrival->ViewValue = ew_FormatDateTime($this->arrival->ViewValue, 0);
		$this->arrival->ViewCustomAttributes = "";

		// departure
		$this->departure->ViewValue = $this->departure->CurrentValue;
		$this->departure->ViewValue = ew_FormatDateTime($this->departure->ViewValue, 0);
		$this->departure->ViewCustomAttributes = "";

		// user
		$this->user->ViewValue = $this->user->CurrentValue;
		$this->user->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// country
		$this->country->ViewValue = $this->country->CurrentValue;
		$this->country->ViewCustomAttributes = "";

		// approve
		$this->approve->ViewValue = $this->approve->CurrentValue;
		$this->approve->ViewCustomAttributes = "";

		// room
		$this->room->ViewValue = $this->room->CurrentValue;
		$this->room->ViewCustomAttributes = "";

		// roomnumber
		$this->roomnumber->ViewValue = $this->roomnumber->CurrentValue;
		$this->roomnumber->ViewCustomAttributes = "";

		// number
		$this->number->ViewValue = $this->number->CurrentValue;
		$this->number->ViewCustomAttributes = "";

		// childs
		$this->childs->ViewValue = $this->childs->CurrentValue;
		$this->childs->ViewCustomAttributes = "";

		// price
		$this->price->ViewValue = $this->price->CurrentValue;
		$this->price->ViewCustomAttributes = "";

		// reservated
		$this->reservated->ViewValue = $this->reservated->CurrentValue;
		$this->reservated->ViewValue = ew_FormatDateTime($this->reservated->ViewValue, 0);
		$this->reservated->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// arrival
			$this->arrival->LinkCustomAttributes = "";
			$this->arrival->HrefValue = "";
			$this->arrival->TooltipValue = "";

			// departure
			$this->departure->LinkCustomAttributes = "";
			$this->departure->HrefValue = "";
			$this->departure->TooltipValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// country
			$this->country->LinkCustomAttributes = "";
			$this->country->HrefValue = "";
			$this->country->TooltipValue = "";

			// approve
			$this->approve->LinkCustomAttributes = "";
			$this->approve->HrefValue = "";
			$this->approve->TooltipValue = "";

			// room
			$this->room->LinkCustomAttributes = "";
			$this->room->HrefValue = "";
			$this->room->TooltipValue = "";

			// roomnumber
			$this->roomnumber->LinkCustomAttributes = "";
			$this->roomnumber->HrefValue = "";
			$this->roomnumber->TooltipValue = "";

			// number
			$this->number->LinkCustomAttributes = "";
			$this->number->HrefValue = "";
			$this->number->TooltipValue = "";

			// childs
			$this->childs->LinkCustomAttributes = "";
			$this->childs->HrefValue = "";
			$this->childs->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// reservated
			$this->reservated->LinkCustomAttributes = "";
			$this->reservated->HrefValue = "";
			$this->reservated->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("wp_reservationslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($wp_reservations_delete)) $wp_reservations_delete = new cwp_reservations_delete();

// Page init
$wp_reservations_delete->Page_Init();

// Page main
$wp_reservations_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$wp_reservations_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fwp_reservationsdelete = new ew_Form("fwp_reservationsdelete", "delete");

// Form_CustomValidate event
fwp_reservationsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwp_reservationsdelete.ValidateRequired = true;
<?php } else { ?>
fwp_reservationsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $wp_reservations_delete->ShowPageHeader(); ?>
<?php
$wp_reservations_delete->ShowMessage();
?>
<form name="fwp_reservationsdelete" id="fwp_reservationsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($wp_reservations_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $wp_reservations_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="wp_reservations">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($wp_reservations_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $wp_reservations->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($wp_reservations->id->Visible) { // id ?>
		<th><span id="elh_wp_reservations_id" class="wp_reservations_id"><?php echo $wp_reservations->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->arrival->Visible) { // arrival ?>
		<th><span id="elh_wp_reservations_arrival" class="wp_reservations_arrival"><?php echo $wp_reservations->arrival->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->departure->Visible) { // departure ?>
		<th><span id="elh_wp_reservations_departure" class="wp_reservations_departure"><?php echo $wp_reservations->departure->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->user->Visible) { // user ?>
		<th><span id="elh_wp_reservations_user" class="wp_reservations_user"><?php echo $wp_reservations->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->name->Visible) { // name ?>
		<th><span id="elh_wp_reservations_name" class="wp_reservations_name"><?php echo $wp_reservations->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->_email->Visible) { // email ?>
		<th><span id="elh_wp_reservations__email" class="wp_reservations__email"><?php echo $wp_reservations->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->country->Visible) { // country ?>
		<th><span id="elh_wp_reservations_country" class="wp_reservations_country"><?php echo $wp_reservations->country->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->approve->Visible) { // approve ?>
		<th><span id="elh_wp_reservations_approve" class="wp_reservations_approve"><?php echo $wp_reservations->approve->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->room->Visible) { // room ?>
		<th><span id="elh_wp_reservations_room" class="wp_reservations_room"><?php echo $wp_reservations->room->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->roomnumber->Visible) { // roomnumber ?>
		<th><span id="elh_wp_reservations_roomnumber" class="wp_reservations_roomnumber"><?php echo $wp_reservations->roomnumber->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->number->Visible) { // number ?>
		<th><span id="elh_wp_reservations_number" class="wp_reservations_number"><?php echo $wp_reservations->number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->childs->Visible) { // childs ?>
		<th><span id="elh_wp_reservations_childs" class="wp_reservations_childs"><?php echo $wp_reservations->childs->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->price->Visible) { // price ?>
		<th><span id="elh_wp_reservations_price" class="wp_reservations_price"><?php echo $wp_reservations->price->FldCaption() ?></span></th>
<?php } ?>
<?php if ($wp_reservations->reservated->Visible) { // reservated ?>
		<th><span id="elh_wp_reservations_reservated" class="wp_reservations_reservated"><?php echo $wp_reservations->reservated->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$wp_reservations_delete->RecCnt = 0;
$i = 0;
while (!$wp_reservations_delete->Recordset->EOF) {
	$wp_reservations_delete->RecCnt++;
	$wp_reservations_delete->RowCnt++;

	// Set row properties
	$wp_reservations->ResetAttrs();
	$wp_reservations->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$wp_reservations_delete->LoadRowValues($wp_reservations_delete->Recordset);

	// Render row
	$wp_reservations_delete->RenderRow();
?>
	<tr<?php echo $wp_reservations->RowAttributes() ?>>
<?php if ($wp_reservations->id->Visible) { // id ?>
		<td<?php echo $wp_reservations->id->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_id" class="wp_reservations_id">
<span<?php echo $wp_reservations->id->ViewAttributes() ?>>
<?php echo $wp_reservations->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->arrival->Visible) { // arrival ?>
		<td<?php echo $wp_reservations->arrival->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_arrival" class="wp_reservations_arrival">
<span<?php echo $wp_reservations->arrival->ViewAttributes() ?>>
<?php echo $wp_reservations->arrival->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->departure->Visible) { // departure ?>
		<td<?php echo $wp_reservations->departure->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_departure" class="wp_reservations_departure">
<span<?php echo $wp_reservations->departure->ViewAttributes() ?>>
<?php echo $wp_reservations->departure->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->user->Visible) { // user ?>
		<td<?php echo $wp_reservations->user->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_user" class="wp_reservations_user">
<span<?php echo $wp_reservations->user->ViewAttributes() ?>>
<?php echo $wp_reservations->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->name->Visible) { // name ?>
		<td<?php echo $wp_reservations->name->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_name" class="wp_reservations_name">
<span<?php echo $wp_reservations->name->ViewAttributes() ?>>
<?php echo $wp_reservations->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->_email->Visible) { // email ?>
		<td<?php echo $wp_reservations->_email->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations__email" class="wp_reservations__email">
<span<?php echo $wp_reservations->_email->ViewAttributes() ?>>
<?php echo $wp_reservations->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->country->Visible) { // country ?>
		<td<?php echo $wp_reservations->country->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_country" class="wp_reservations_country">
<span<?php echo $wp_reservations->country->ViewAttributes() ?>>
<?php echo $wp_reservations->country->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->approve->Visible) { // approve ?>
		<td<?php echo $wp_reservations->approve->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_approve" class="wp_reservations_approve">
<span<?php echo $wp_reservations->approve->ViewAttributes() ?>>
<?php echo $wp_reservations->approve->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->room->Visible) { // room ?>
		<td<?php echo $wp_reservations->room->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_room" class="wp_reservations_room">
<span<?php echo $wp_reservations->room->ViewAttributes() ?>>
<?php echo $wp_reservations->room->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->roomnumber->Visible) { // roomnumber ?>
		<td<?php echo $wp_reservations->roomnumber->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_roomnumber" class="wp_reservations_roomnumber">
<span<?php echo $wp_reservations->roomnumber->ViewAttributes() ?>>
<?php echo $wp_reservations->roomnumber->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->number->Visible) { // number ?>
		<td<?php echo $wp_reservations->number->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_number" class="wp_reservations_number">
<span<?php echo $wp_reservations->number->ViewAttributes() ?>>
<?php echo $wp_reservations->number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->childs->Visible) { // childs ?>
		<td<?php echo $wp_reservations->childs->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_childs" class="wp_reservations_childs">
<span<?php echo $wp_reservations->childs->ViewAttributes() ?>>
<?php echo $wp_reservations->childs->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->price->Visible) { // price ?>
		<td<?php echo $wp_reservations->price->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_price" class="wp_reservations_price">
<span<?php echo $wp_reservations->price->ViewAttributes() ?>>
<?php echo $wp_reservations->price->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($wp_reservations->reservated->Visible) { // reservated ?>
		<td<?php echo $wp_reservations->reservated->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_delete->RowCnt ?>_wp_reservations_reservated" class="wp_reservations_reservated">
<span<?php echo $wp_reservations->reservated->ViewAttributes() ?>>
<?php echo $wp_reservations->reservated->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$wp_reservations_delete->Recordset->MoveNext();
}
$wp_reservations_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $wp_reservations_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fwp_reservationsdelete.Init();
</script>
<?php
$wp_reservations_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$wp_reservations_delete->Page_Terminate();
?>
