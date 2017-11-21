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

$wp_reservations_view = NULL; // Initialize page object first

class cwp_reservations_view extends cwp_reservations {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{AA939E07-3D1E-49D2-B9FF-BB5F5AC80C48}";

	// Table name
	var $TableName = 'wp_reservations';

	// Page object name
	var $PageObjName = 'wp_reservations_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'wp_reservations', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$sReturnUrl = "wp_reservationslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "wp_reservationslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "wp_reservationslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("wp_reservationslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($wp_reservations_view)) $wp_reservations_view = new cwp_reservations_view();

// Page init
$wp_reservations_view->Page_Init();

// Page main
$wp_reservations_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$wp_reservations_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fwp_reservationsview = new ew_Form("fwp_reservationsview", "view");

// Form_CustomValidate event
fwp_reservationsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwp_reservationsview.ValidateRequired = true;
<?php } else { ?>
fwp_reservationsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$wp_reservations_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $wp_reservations_view->ExportOptions->Render("body") ?>
<?php
	foreach ($wp_reservations_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$wp_reservations_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $wp_reservations_view->ShowPageHeader(); ?>
<?php
$wp_reservations_view->ShowMessage();
?>
<form name="fwp_reservationsview" id="fwp_reservationsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($wp_reservations_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $wp_reservations_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="wp_reservations">
<?php if ($wp_reservations_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($wp_reservations->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_wp_reservations_id"><?php echo $wp_reservations->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $wp_reservations->id->CellAttributes() ?>>
<span id="el_wp_reservations_id">
<span<?php echo $wp_reservations->id->ViewAttributes() ?>>
<?php echo $wp_reservations->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->arrival->Visible) { // arrival ?>
	<tr id="r_arrival">
		<td><span id="elh_wp_reservations_arrival"><?php echo $wp_reservations->arrival->FldCaption() ?></span></td>
		<td data-name="arrival"<?php echo $wp_reservations->arrival->CellAttributes() ?>>
<span id="el_wp_reservations_arrival">
<span<?php echo $wp_reservations->arrival->ViewAttributes() ?>>
<?php echo $wp_reservations->arrival->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->departure->Visible) { // departure ?>
	<tr id="r_departure">
		<td><span id="elh_wp_reservations_departure"><?php echo $wp_reservations->departure->FldCaption() ?></span></td>
		<td data-name="departure"<?php echo $wp_reservations->departure->CellAttributes() ?>>
<span id="el_wp_reservations_departure">
<span<?php echo $wp_reservations->departure->ViewAttributes() ?>>
<?php echo $wp_reservations->departure->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_wp_reservations_user"><?php echo $wp_reservations->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $wp_reservations->user->CellAttributes() ?>>
<span id="el_wp_reservations_user">
<span<?php echo $wp_reservations->user->ViewAttributes() ?>>
<?php echo $wp_reservations->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_wp_reservations_name"><?php echo $wp_reservations->name->FldCaption() ?></span></td>
		<td data-name="name"<?php echo $wp_reservations->name->CellAttributes() ?>>
<span id="el_wp_reservations_name">
<span<?php echo $wp_reservations->name->ViewAttributes() ?>>
<?php echo $wp_reservations->name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_wp_reservations__email"><?php echo $wp_reservations->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $wp_reservations->_email->CellAttributes() ?>>
<span id="el_wp_reservations__email">
<span<?php echo $wp_reservations->_email->ViewAttributes() ?>>
<?php echo $wp_reservations->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->country->Visible) { // country ?>
	<tr id="r_country">
		<td><span id="elh_wp_reservations_country"><?php echo $wp_reservations->country->FldCaption() ?></span></td>
		<td data-name="country"<?php echo $wp_reservations->country->CellAttributes() ?>>
<span id="el_wp_reservations_country">
<span<?php echo $wp_reservations->country->ViewAttributes() ?>>
<?php echo $wp_reservations->country->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->approve->Visible) { // approve ?>
	<tr id="r_approve">
		<td><span id="elh_wp_reservations_approve"><?php echo $wp_reservations->approve->FldCaption() ?></span></td>
		<td data-name="approve"<?php echo $wp_reservations->approve->CellAttributes() ?>>
<span id="el_wp_reservations_approve">
<span<?php echo $wp_reservations->approve->ViewAttributes() ?>>
<?php echo $wp_reservations->approve->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->room->Visible) { // room ?>
	<tr id="r_room">
		<td><span id="elh_wp_reservations_room"><?php echo $wp_reservations->room->FldCaption() ?></span></td>
		<td data-name="room"<?php echo $wp_reservations->room->CellAttributes() ?>>
<span id="el_wp_reservations_room">
<span<?php echo $wp_reservations->room->ViewAttributes() ?>>
<?php echo $wp_reservations->room->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->roomnumber->Visible) { // roomnumber ?>
	<tr id="r_roomnumber">
		<td><span id="elh_wp_reservations_roomnumber"><?php echo $wp_reservations->roomnumber->FldCaption() ?></span></td>
		<td data-name="roomnumber"<?php echo $wp_reservations->roomnumber->CellAttributes() ?>>
<span id="el_wp_reservations_roomnumber">
<span<?php echo $wp_reservations->roomnumber->ViewAttributes() ?>>
<?php echo $wp_reservations->roomnumber->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->number->Visible) { // number ?>
	<tr id="r_number">
		<td><span id="elh_wp_reservations_number"><?php echo $wp_reservations->number->FldCaption() ?></span></td>
		<td data-name="number"<?php echo $wp_reservations->number->CellAttributes() ?>>
<span id="el_wp_reservations_number">
<span<?php echo $wp_reservations->number->ViewAttributes() ?>>
<?php echo $wp_reservations->number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->childs->Visible) { // childs ?>
	<tr id="r_childs">
		<td><span id="elh_wp_reservations_childs"><?php echo $wp_reservations->childs->FldCaption() ?></span></td>
		<td data-name="childs"<?php echo $wp_reservations->childs->CellAttributes() ?>>
<span id="el_wp_reservations_childs">
<span<?php echo $wp_reservations->childs->ViewAttributes() ?>>
<?php echo $wp_reservations->childs->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->price->Visible) { // price ?>
	<tr id="r_price">
		<td><span id="elh_wp_reservations_price"><?php echo $wp_reservations->price->FldCaption() ?></span></td>
		<td data-name="price"<?php echo $wp_reservations->price->CellAttributes() ?>>
<span id="el_wp_reservations_price">
<span<?php echo $wp_reservations->price->ViewAttributes() ?>>
<?php echo $wp_reservations->price->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->custom->Visible) { // custom ?>
	<tr id="r_custom">
		<td><span id="elh_wp_reservations_custom"><?php echo $wp_reservations->custom->FldCaption() ?></span></td>
		<td data-name="custom"<?php echo $wp_reservations->custom->CellAttributes() ?>>
<span id="el_wp_reservations_custom">
<span<?php echo $wp_reservations->custom->ViewAttributes() ?>>
<?php echo $wp_reservations->custom->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->customp->Visible) { // customp ?>
	<tr id="r_customp">
		<td><span id="elh_wp_reservations_customp"><?php echo $wp_reservations->customp->FldCaption() ?></span></td>
		<td data-name="customp"<?php echo $wp_reservations->customp->CellAttributes() ?>>
<span id="el_wp_reservations_customp">
<span<?php echo $wp_reservations->customp->ViewAttributes() ?>>
<?php echo $wp_reservations->customp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($wp_reservations->reservated->Visible) { // reservated ?>
	<tr id="r_reservated">
		<td><span id="elh_wp_reservations_reservated"><?php echo $wp_reservations->reservated->FldCaption() ?></span></td>
		<td data-name="reservated"<?php echo $wp_reservations->reservated->CellAttributes() ?>>
<span id="el_wp_reservations_reservated">
<span<?php echo $wp_reservations->reservated->ViewAttributes() ?>>
<?php echo $wp_reservations->reservated->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fwp_reservationsview.Init();
</script>
<?php
$wp_reservations_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$wp_reservations_view->Page_Terminate();
?>
