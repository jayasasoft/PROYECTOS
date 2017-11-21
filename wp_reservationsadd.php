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

$wp_reservations_add = NULL; // Initialize page object first

class cwp_reservations_add extends cwp_reservations {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{AA939E07-3D1E-49D2-B9FF-BB5F5AC80C48}";

	// Table name
	var $TableName = 'wp_reservations';

	// Page object name
	var $PageObjName = 'wp_reservations_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("wp_reservationslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
		$this->custom->SetVisibility();
		$this->customp->SetVisibility();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("wp_reservationslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "wp_reservationslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "wp_reservationsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->arrival->CurrentValue = NULL;
		$this->arrival->OldValue = $this->arrival->CurrentValue;
		$this->departure->CurrentValue = NULL;
		$this->departure->OldValue = $this->departure->CurrentValue;
		$this->user->CurrentValue = NULL;
		$this->user->OldValue = $this->user->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->country->CurrentValue = NULL;
		$this->country->OldValue = $this->country->CurrentValue;
		$this->approve->CurrentValue = NULL;
		$this->approve->OldValue = $this->approve->CurrentValue;
		$this->room->CurrentValue = NULL;
		$this->room->OldValue = $this->room->CurrentValue;
		$this->roomnumber->CurrentValue = NULL;
		$this->roomnumber->OldValue = $this->roomnumber->CurrentValue;
		$this->number->CurrentValue = NULL;
		$this->number->OldValue = $this->number->CurrentValue;
		$this->childs->CurrentValue = NULL;
		$this->childs->OldValue = $this->childs->CurrentValue;
		$this->price->CurrentValue = NULL;
		$this->price->OldValue = $this->price->CurrentValue;
		$this->custom->CurrentValue = NULL;
		$this->custom->OldValue = $this->custom->CurrentValue;
		$this->customp->CurrentValue = NULL;
		$this->customp->OldValue = $this->customp->CurrentValue;
		$this->reservated->CurrentValue = NULL;
		$this->reservated->OldValue = $this->reservated->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->arrival->FldIsDetailKey) {
			$this->arrival->setFormValue($objForm->GetValue("x_arrival"));
			$this->arrival->CurrentValue = ew_UnFormatDateTime($this->arrival->CurrentValue, 0);
		}
		if (!$this->departure->FldIsDetailKey) {
			$this->departure->setFormValue($objForm->GetValue("x_departure"));
			$this->departure->CurrentValue = ew_UnFormatDateTime($this->departure->CurrentValue, 0);
		}
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->country->FldIsDetailKey) {
			$this->country->setFormValue($objForm->GetValue("x_country"));
		}
		if (!$this->approve->FldIsDetailKey) {
			$this->approve->setFormValue($objForm->GetValue("x_approve"));
		}
		if (!$this->room->FldIsDetailKey) {
			$this->room->setFormValue($objForm->GetValue("x_room"));
		}
		if (!$this->roomnumber->FldIsDetailKey) {
			$this->roomnumber->setFormValue($objForm->GetValue("x_roomnumber"));
		}
		if (!$this->number->FldIsDetailKey) {
			$this->number->setFormValue($objForm->GetValue("x_number"));
		}
		if (!$this->childs->FldIsDetailKey) {
			$this->childs->setFormValue($objForm->GetValue("x_childs"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->custom->FldIsDetailKey) {
			$this->custom->setFormValue($objForm->GetValue("x_custom"));
		}
		if (!$this->customp->FldIsDetailKey) {
			$this->customp->setFormValue($objForm->GetValue("x_customp"));
		}
		if (!$this->reservated->FldIsDetailKey) {
			$this->reservated->setFormValue($objForm->GetValue("x_reservated"));
			$this->reservated->CurrentValue = ew_UnFormatDateTime($this->reservated->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->arrival->CurrentValue = $this->arrival->FormValue;
		$this->arrival->CurrentValue = ew_UnFormatDateTime($this->arrival->CurrentValue, 0);
		$this->departure->CurrentValue = $this->departure->FormValue;
		$this->departure->CurrentValue = ew_UnFormatDateTime($this->departure->CurrentValue, 0);
		$this->user->CurrentValue = $this->user->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->country->CurrentValue = $this->country->FormValue;
		$this->approve->CurrentValue = $this->approve->FormValue;
		$this->room->CurrentValue = $this->room->FormValue;
		$this->roomnumber->CurrentValue = $this->roomnumber->FormValue;
		$this->number->CurrentValue = $this->number->FormValue;
		$this->childs->CurrentValue = $this->childs->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->custom->CurrentValue = $this->custom->FormValue;
		$this->customp->CurrentValue = $this->customp->FormValue;
		$this->reservated->CurrentValue = $this->reservated->FormValue;
		$this->reservated->CurrentValue = ew_UnFormatDateTime($this->reservated->CurrentValue, 0);
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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

		// custom
		$this->custom->ViewValue = $this->custom->CurrentValue;
		$this->custom->ViewCustomAttributes = "";

		// customp
		$this->customp->ViewValue = $this->customp->CurrentValue;
		$this->customp->ViewCustomAttributes = "";

		// reservated
		$this->reservated->ViewValue = $this->reservated->CurrentValue;
		$this->reservated->ViewValue = ew_FormatDateTime($this->reservated->ViewValue, 0);
		$this->reservated->ViewCustomAttributes = "";

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

			// custom
			$this->custom->LinkCustomAttributes = "";
			$this->custom->HrefValue = "";
			$this->custom->TooltipValue = "";

			// customp
			$this->customp->LinkCustomAttributes = "";
			$this->customp->HrefValue = "";
			$this->customp->TooltipValue = "";

			// reservated
			$this->reservated->LinkCustomAttributes = "";
			$this->reservated->HrefValue = "";
			$this->reservated->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// arrival
			$this->arrival->EditAttrs["class"] = "form-control";
			$this->arrival->EditCustomAttributes = "";
			$this->arrival->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->arrival->CurrentValue, 8));
			$this->arrival->PlaceHolder = ew_RemoveHtml($this->arrival->FldCaption());

			// departure
			$this->departure->EditAttrs["class"] = "form-control";
			$this->departure->EditCustomAttributes = "";
			$this->departure->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->departure->CurrentValue, 8));
			$this->departure->PlaceHolder = ew_RemoveHtml($this->departure->FldCaption());

			// user
			$this->user->EditAttrs["class"] = "form-control";
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->CurrentValue);
			$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// country
			$this->country->EditAttrs["class"] = "form-control";
			$this->country->EditCustomAttributes = "";
			$this->country->EditValue = ew_HtmlEncode($this->country->CurrentValue);
			$this->country->PlaceHolder = ew_RemoveHtml($this->country->FldCaption());

			// approve
			$this->approve->EditAttrs["class"] = "form-control";
			$this->approve->EditCustomAttributes = "";
			$this->approve->EditValue = ew_HtmlEncode($this->approve->CurrentValue);
			$this->approve->PlaceHolder = ew_RemoveHtml($this->approve->FldCaption());

			// room
			$this->room->EditAttrs["class"] = "form-control";
			$this->room->EditCustomAttributes = "";
			$this->room->EditValue = ew_HtmlEncode($this->room->CurrentValue);
			$this->room->PlaceHolder = ew_RemoveHtml($this->room->FldCaption());

			// roomnumber
			$this->roomnumber->EditAttrs["class"] = "form-control";
			$this->roomnumber->EditCustomAttributes = "";
			$this->roomnumber->EditValue = ew_HtmlEncode($this->roomnumber->CurrentValue);
			$this->roomnumber->PlaceHolder = ew_RemoveHtml($this->roomnumber->FldCaption());

			// number
			$this->number->EditAttrs["class"] = "form-control";
			$this->number->EditCustomAttributes = "";
			$this->number->EditValue = ew_HtmlEncode($this->number->CurrentValue);
			$this->number->PlaceHolder = ew_RemoveHtml($this->number->FldCaption());

			// childs
			$this->childs->EditAttrs["class"] = "form-control";
			$this->childs->EditCustomAttributes = "";
			$this->childs->EditValue = ew_HtmlEncode($this->childs->CurrentValue);
			$this->childs->PlaceHolder = ew_RemoveHtml($this->childs->FldCaption());

			// price
			$this->price->EditAttrs["class"] = "form-control";
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());

			// custom
			$this->custom->EditAttrs["class"] = "form-control";
			$this->custom->EditCustomAttributes = "";
			$this->custom->EditValue = ew_HtmlEncode($this->custom->CurrentValue);
			$this->custom->PlaceHolder = ew_RemoveHtml($this->custom->FldCaption());

			// customp
			$this->customp->EditAttrs["class"] = "form-control";
			$this->customp->EditCustomAttributes = "";
			$this->customp->EditValue = ew_HtmlEncode($this->customp->CurrentValue);
			$this->customp->PlaceHolder = ew_RemoveHtml($this->customp->FldCaption());

			// reservated
			$this->reservated->EditAttrs["class"] = "form-control";
			$this->reservated->EditCustomAttributes = "";
			$this->reservated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->reservated->CurrentValue, 8));
			$this->reservated->PlaceHolder = ew_RemoveHtml($this->reservated->FldCaption());

			// Add refer script
			// arrival

			$this->arrival->LinkCustomAttributes = "";
			$this->arrival->HrefValue = "";

			// departure
			$this->departure->LinkCustomAttributes = "";
			$this->departure->HrefValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// country
			$this->country->LinkCustomAttributes = "";
			$this->country->HrefValue = "";

			// approve
			$this->approve->LinkCustomAttributes = "";
			$this->approve->HrefValue = "";

			// room
			$this->room->LinkCustomAttributes = "";
			$this->room->HrefValue = "";

			// roomnumber
			$this->roomnumber->LinkCustomAttributes = "";
			$this->roomnumber->HrefValue = "";

			// number
			$this->number->LinkCustomAttributes = "";
			$this->number->HrefValue = "";

			// childs
			$this->childs->LinkCustomAttributes = "";
			$this->childs->HrefValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";

			// custom
			$this->custom->LinkCustomAttributes = "";
			$this->custom->HrefValue = "";

			// customp
			$this->customp->LinkCustomAttributes = "";
			$this->customp->HrefValue = "";

			// reservated
			$this->reservated->LinkCustomAttributes = "";
			$this->reservated->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->arrival->FldIsDetailKey && !is_null($this->arrival->FormValue) && $this->arrival->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->arrival->FldCaption(), $this->arrival->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->arrival->FormValue)) {
			ew_AddMessage($gsFormError, $this->arrival->FldErrMsg());
		}
		if (!$this->departure->FldIsDetailKey && !is_null($this->departure->FormValue) && $this->departure->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->departure->FldCaption(), $this->departure->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->departure->FormValue)) {
			ew_AddMessage($gsFormError, $this->departure->FldErrMsg());
		}
		if (!$this->user->FldIsDetailKey && !is_null($this->user->FormValue) && $this->user->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user->FldCaption(), $this->user->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->user->FormValue)) {
			ew_AddMessage($gsFormError, $this->user->FldErrMsg());
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name->FldCaption(), $this->name->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->country->FldIsDetailKey && !is_null($this->country->FormValue) && $this->country->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->country->FldCaption(), $this->country->ReqErrMsg));
		}
		if (!$this->approve->FldIsDetailKey && !is_null($this->approve->FormValue) && $this->approve->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approve->FldCaption(), $this->approve->ReqErrMsg));
		}
		if (!$this->roomnumber->FldIsDetailKey && !is_null($this->roomnumber->FormValue) && $this->roomnumber->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->roomnumber->FldCaption(), $this->roomnumber->ReqErrMsg));
		}
		if (!$this->number->FldIsDetailKey && !is_null($this->number->FormValue) && $this->number->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->number->FldCaption(), $this->number->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->number->FormValue)) {
			ew_AddMessage($gsFormError, $this->number->FldErrMsg());
		}
		if (!$this->childs->FldIsDetailKey && !is_null($this->childs->FormValue) && $this->childs->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->childs->FldCaption(), $this->childs->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->childs->FormValue)) {
			ew_AddMessage($gsFormError, $this->childs->FldErrMsg());
		}
		if (!$this->price->FldIsDetailKey && !is_null($this->price->FormValue) && $this->price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->price->FldCaption(), $this->price->ReqErrMsg));
		}
		if (!$this->custom->FldIsDetailKey && !is_null($this->custom->FormValue) && $this->custom->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->custom->FldCaption(), $this->custom->ReqErrMsg));
		}
		if (!$this->customp->FldIsDetailKey && !is_null($this->customp->FormValue) && $this->customp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customp->FldCaption(), $this->customp->ReqErrMsg));
		}
		if (!$this->reservated->FldIsDetailKey && !is_null($this->reservated->FormValue) && $this->reservated->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reservated->FldCaption(), $this->reservated->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->reservated->FormValue)) {
			ew_AddMessage($gsFormError, $this->reservated->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// arrival
		$this->arrival->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->arrival->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// departure
		$this->departure->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->departure->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// user
		$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, 0, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// country
		$this->country->SetDbValueDef($rsnew, $this->country->CurrentValue, "", FALSE);

		// approve
		$this->approve->SetDbValueDef($rsnew, $this->approve->CurrentValue, "", FALSE);

		// room
		$this->room->SetDbValueDef($rsnew, $this->room->CurrentValue, NULL, FALSE);

		// roomnumber
		$this->roomnumber->SetDbValueDef($rsnew, $this->roomnumber->CurrentValue, "", FALSE);

		// number
		$this->number->SetDbValueDef($rsnew, $this->number->CurrentValue, 0, FALSE);

		// childs
		$this->childs->SetDbValueDef($rsnew, $this->childs->CurrentValue, 0, FALSE);

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, "", FALSE);

		// custom
		$this->custom->SetDbValueDef($rsnew, $this->custom->CurrentValue, "", FALSE);

		// customp
		$this->customp->SetDbValueDef($rsnew, $this->customp->CurrentValue, "", FALSE);

		// reservated
		$this->reservated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->reservated->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("wp_reservationslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($wp_reservations_add)) $wp_reservations_add = new cwp_reservations_add();

// Page init
$wp_reservations_add->Page_Init();

// Page main
$wp_reservations_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$wp_reservations_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fwp_reservationsadd = new ew_Form("fwp_reservationsadd", "add");

// Validate form
fwp_reservationsadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_arrival");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->arrival->FldCaption(), $wp_reservations->arrival->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_arrival");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($wp_reservations->arrival->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_departure");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->departure->FldCaption(), $wp_reservations->departure->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_departure");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($wp_reservations->departure->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->user->FldCaption(), $wp_reservations->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($wp_reservations->user->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->name->FldCaption(), $wp_reservations->name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->_email->FldCaption(), $wp_reservations->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_country");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->country->FldCaption(), $wp_reservations->country->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_approve");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->approve->FldCaption(), $wp_reservations->approve->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_roomnumber");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->roomnumber->FldCaption(), $wp_reservations->roomnumber->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->number->FldCaption(), $wp_reservations->number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_number");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($wp_reservations->number->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_childs");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->childs->FldCaption(), $wp_reservations->childs->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_childs");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($wp_reservations->childs->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->price->FldCaption(), $wp_reservations->price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_custom");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->custom->FldCaption(), $wp_reservations->custom->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_customp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->customp->FldCaption(), $wp_reservations->customp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reservated");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $wp_reservations->reservated->FldCaption(), $wp_reservations->reservated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reservated");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($wp_reservations->reservated->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fwp_reservationsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwp_reservationsadd.ValidateRequired = true;
<?php } else { ?>
fwp_reservationsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$wp_reservations_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $wp_reservations_add->ShowPageHeader(); ?>
<?php
$wp_reservations_add->ShowMessage();
?>
<form name="fwp_reservationsadd" id="fwp_reservationsadd" class="<?php echo $wp_reservations_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($wp_reservations_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $wp_reservations_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="wp_reservations">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($wp_reservations_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($wp_reservations->arrival->Visible) { // arrival ?>
	<div id="r_arrival" class="form-group">
		<label id="elh_wp_reservations_arrival" for="x_arrival" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->arrival->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->arrival->CellAttributes() ?>>
<span id="el_wp_reservations_arrival">
<input type="text" data-table="wp_reservations" data-field="x_arrival" name="x_arrival" id="x_arrival" placeholder="<?php echo ew_HtmlEncode($wp_reservations->arrival->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->arrival->EditValue ?>"<?php echo $wp_reservations->arrival->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->arrival->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->departure->Visible) { // departure ?>
	<div id="r_departure" class="form-group">
		<label id="elh_wp_reservations_departure" for="x_departure" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->departure->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->departure->CellAttributes() ?>>
<span id="el_wp_reservations_departure">
<input type="text" data-table="wp_reservations" data-field="x_departure" name="x_departure" id="x_departure" placeholder="<?php echo ew_HtmlEncode($wp_reservations->departure->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->departure->EditValue ?>"<?php echo $wp_reservations->departure->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->departure->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_wp_reservations_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->user->CellAttributes() ?>>
<span id="el_wp_reservations_user">
<input type="text" data-table="wp_reservations" data-field="x_user" name="x_user" id="x_user" size="30" placeholder="<?php echo ew_HtmlEncode($wp_reservations->user->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->user->EditValue ?>"<?php echo $wp_reservations->user->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_wp_reservations_name" for="x_name" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->name->CellAttributes() ?>>
<span id="el_wp_reservations_name">
<input type="text" data-table="wp_reservations" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($wp_reservations->name->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->name->EditValue ?>"<?php echo $wp_reservations->name->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_wp_reservations__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->_email->CellAttributes() ?>>
<span id="el_wp_reservations__email">
<input type="text" data-table="wp_reservations" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($wp_reservations->_email->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->_email->EditValue ?>"<?php echo $wp_reservations->_email->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->country->Visible) { // country ?>
	<div id="r_country" class="form-group">
		<label id="elh_wp_reservations_country" for="x_country" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->country->CellAttributes() ?>>
<span id="el_wp_reservations_country">
<input type="text" data-table="wp_reservations" data-field="x_country" name="x_country" id="x_country" size="30" maxlength="4" placeholder="<?php echo ew_HtmlEncode($wp_reservations->country->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->country->EditValue ?>"<?php echo $wp_reservations->country->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->approve->Visible) { // approve ?>
	<div id="r_approve" class="form-group">
		<label id="elh_wp_reservations_approve" for="x_approve" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->approve->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->approve->CellAttributes() ?>>
<span id="el_wp_reservations_approve">
<input type="text" data-table="wp_reservations" data-field="x_approve" name="x_approve" id="x_approve" size="30" maxlength="3" placeholder="<?php echo ew_HtmlEncode($wp_reservations->approve->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->approve->EditValue ?>"<?php echo $wp_reservations->approve->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->approve->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->room->Visible) { // room ?>
	<div id="r_room" class="form-group">
		<label id="elh_wp_reservations_room" for="x_room" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->room->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->room->CellAttributes() ?>>
<span id="el_wp_reservations_room">
<input type="text" data-table="wp_reservations" data-field="x_room" name="x_room" id="x_room" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($wp_reservations->room->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->room->EditValue ?>"<?php echo $wp_reservations->room->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->room->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->roomnumber->Visible) { // roomnumber ?>
	<div id="r_roomnumber" class="form-group">
		<label id="elh_wp_reservations_roomnumber" for="x_roomnumber" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->roomnumber->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->roomnumber->CellAttributes() ?>>
<span id="el_wp_reservations_roomnumber">
<input type="text" data-table="wp_reservations" data-field="x_roomnumber" name="x_roomnumber" id="x_roomnumber" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($wp_reservations->roomnumber->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->roomnumber->EditValue ?>"<?php echo $wp_reservations->roomnumber->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->roomnumber->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->number->Visible) { // number ?>
	<div id="r_number" class="form-group">
		<label id="elh_wp_reservations_number" for="x_number" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->number->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->number->CellAttributes() ?>>
<span id="el_wp_reservations_number">
<input type="text" data-table="wp_reservations" data-field="x_number" name="x_number" id="x_number" size="30" placeholder="<?php echo ew_HtmlEncode($wp_reservations->number->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->number->EditValue ?>"<?php echo $wp_reservations->number->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->childs->Visible) { // childs ?>
	<div id="r_childs" class="form-group">
		<label id="elh_wp_reservations_childs" for="x_childs" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->childs->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->childs->CellAttributes() ?>>
<span id="el_wp_reservations_childs">
<input type="text" data-table="wp_reservations" data-field="x_childs" name="x_childs" id="x_childs" size="30" placeholder="<?php echo ew_HtmlEncode($wp_reservations->childs->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->childs->EditValue ?>"<?php echo $wp_reservations->childs->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->childs->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->price->Visible) { // price ?>
	<div id="r_price" class="form-group">
		<label id="elh_wp_reservations_price" for="x_price" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->price->CellAttributes() ?>>
<span id="el_wp_reservations_price">
<input type="text" data-table="wp_reservations" data-field="x_price" name="x_price" id="x_price" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($wp_reservations->price->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->price->EditValue ?>"<?php echo $wp_reservations->price->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->custom->Visible) { // custom ?>
	<div id="r_custom" class="form-group">
		<label id="elh_wp_reservations_custom" for="x_custom" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->custom->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->custom->CellAttributes() ?>>
<span id="el_wp_reservations_custom">
<textarea data-table="wp_reservations" data-field="x_custom" name="x_custom" id="x_custom" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($wp_reservations->custom->getPlaceHolder()) ?>"<?php echo $wp_reservations->custom->EditAttributes() ?>><?php echo $wp_reservations->custom->EditValue ?></textarea>
</span>
<?php echo $wp_reservations->custom->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->customp->Visible) { // customp ?>
	<div id="r_customp" class="form-group">
		<label id="elh_wp_reservations_customp" for="x_customp" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->customp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->customp->CellAttributes() ?>>
<span id="el_wp_reservations_customp">
<textarea data-table="wp_reservations" data-field="x_customp" name="x_customp" id="x_customp" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($wp_reservations->customp->getPlaceHolder()) ?>"<?php echo $wp_reservations->customp->EditAttributes() ?>><?php echo $wp_reservations->customp->EditValue ?></textarea>
</span>
<?php echo $wp_reservations->customp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($wp_reservations->reservated->Visible) { // reservated ?>
	<div id="r_reservated" class="form-group">
		<label id="elh_wp_reservations_reservated" for="x_reservated" class="col-sm-2 control-label ewLabel"><?php echo $wp_reservations->reservated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $wp_reservations->reservated->CellAttributes() ?>>
<span id="el_wp_reservations_reservated">
<input type="text" data-table="wp_reservations" data-field="x_reservated" name="x_reservated" id="x_reservated" placeholder="<?php echo ew_HtmlEncode($wp_reservations->reservated->getPlaceHolder()) ?>" value="<?php echo $wp_reservations->reservated->EditValue ?>"<?php echo $wp_reservations->reservated->EditAttributes() ?>>
</span>
<?php echo $wp_reservations->reservated->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$wp_reservations_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $wp_reservations_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fwp_reservationsadd.Init();
</script>
<?php
$wp_reservations_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$wp_reservations_add->Page_Terminate();
?>
