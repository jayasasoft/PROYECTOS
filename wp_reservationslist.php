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

$wp_reservations_list = NULL; // Initialize page object first

class cwp_reservations_list extends cwp_reservations {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{AA939E07-3D1E-49D2-B9FF-BB5F5AC80C48}";

	// Table name
	var $TableName = 'wp_reservations';

	// Page object name
	var $PageObjName = 'wp_reservations_list';

	// Grid form hidden field names
	var $FormName = 'fwp_reservationslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "wp_reservationsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "wp_reservationsdelete.php";
		$this->MultiUpdateUrl = "wp_reservationsupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'wp_reservations', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fwp_reservationslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 22;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 22; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fwp_reservationslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->arrival->AdvancedSearch->ToJSON(), ","); // Field arrival
		$sFilterList = ew_Concat($sFilterList, $this->departure->AdvancedSearch->ToJSON(), ","); // Field departure
		$sFilterList = ew_Concat($sFilterList, $this->user->AdvancedSearch->ToJSON(), ","); // Field user
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJSON(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->country->AdvancedSearch->ToJSON(), ","); // Field country
		$sFilterList = ew_Concat($sFilterList, $this->approve->AdvancedSearch->ToJSON(), ","); // Field approve
		$sFilterList = ew_Concat($sFilterList, $this->room->AdvancedSearch->ToJSON(), ","); // Field room
		$sFilterList = ew_Concat($sFilterList, $this->roomnumber->AdvancedSearch->ToJSON(), ","); // Field roomnumber
		$sFilterList = ew_Concat($sFilterList, $this->number->AdvancedSearch->ToJSON(), ","); // Field number
		$sFilterList = ew_Concat($sFilterList, $this->childs->AdvancedSearch->ToJSON(), ","); // Field childs
		$sFilterList = ew_Concat($sFilterList, $this->price->AdvancedSearch->ToJSON(), ","); // Field price
		$sFilterList = ew_Concat($sFilterList, $this->custom->AdvancedSearch->ToJSON(), ","); // Field custom
		$sFilterList = ew_Concat($sFilterList, $this->customp->AdvancedSearch->ToJSON(), ","); // Field customp
		$sFilterList = ew_Concat($sFilterList, $this->reservated->AdvancedSearch->ToJSON(), ","); // Field reservated
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fwp_reservationslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field arrival
		$this->arrival->AdvancedSearch->SearchValue = @$filter["x_arrival"];
		$this->arrival->AdvancedSearch->SearchOperator = @$filter["z_arrival"];
		$this->arrival->AdvancedSearch->SearchCondition = @$filter["v_arrival"];
		$this->arrival->AdvancedSearch->SearchValue2 = @$filter["y_arrival"];
		$this->arrival->AdvancedSearch->SearchOperator2 = @$filter["w_arrival"];
		$this->arrival->AdvancedSearch->Save();

		// Field departure
		$this->departure->AdvancedSearch->SearchValue = @$filter["x_departure"];
		$this->departure->AdvancedSearch->SearchOperator = @$filter["z_departure"];
		$this->departure->AdvancedSearch->SearchCondition = @$filter["v_departure"];
		$this->departure->AdvancedSearch->SearchValue2 = @$filter["y_departure"];
		$this->departure->AdvancedSearch->SearchOperator2 = @$filter["w_departure"];
		$this->departure->AdvancedSearch->Save();

		// Field user
		$this->user->AdvancedSearch->SearchValue = @$filter["x_user"];
		$this->user->AdvancedSearch->SearchOperator = @$filter["z_user"];
		$this->user->AdvancedSearch->SearchCondition = @$filter["v_user"];
		$this->user->AdvancedSearch->SearchValue2 = @$filter["y_user"];
		$this->user->AdvancedSearch->SearchOperator2 = @$filter["w_user"];
		$this->user->AdvancedSearch->Save();

		// Field name
		$this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
		$this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
		$this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
		$this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
		$this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
		$this->name->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field country
		$this->country->AdvancedSearch->SearchValue = @$filter["x_country"];
		$this->country->AdvancedSearch->SearchOperator = @$filter["z_country"];
		$this->country->AdvancedSearch->SearchCondition = @$filter["v_country"];
		$this->country->AdvancedSearch->SearchValue2 = @$filter["y_country"];
		$this->country->AdvancedSearch->SearchOperator2 = @$filter["w_country"];
		$this->country->AdvancedSearch->Save();

		// Field approve
		$this->approve->AdvancedSearch->SearchValue = @$filter["x_approve"];
		$this->approve->AdvancedSearch->SearchOperator = @$filter["z_approve"];
		$this->approve->AdvancedSearch->SearchCondition = @$filter["v_approve"];
		$this->approve->AdvancedSearch->SearchValue2 = @$filter["y_approve"];
		$this->approve->AdvancedSearch->SearchOperator2 = @$filter["w_approve"];
		$this->approve->AdvancedSearch->Save();

		// Field room
		$this->room->AdvancedSearch->SearchValue = @$filter["x_room"];
		$this->room->AdvancedSearch->SearchOperator = @$filter["z_room"];
		$this->room->AdvancedSearch->SearchCondition = @$filter["v_room"];
		$this->room->AdvancedSearch->SearchValue2 = @$filter["y_room"];
		$this->room->AdvancedSearch->SearchOperator2 = @$filter["w_room"];
		$this->room->AdvancedSearch->Save();

		// Field roomnumber
		$this->roomnumber->AdvancedSearch->SearchValue = @$filter["x_roomnumber"];
		$this->roomnumber->AdvancedSearch->SearchOperator = @$filter["z_roomnumber"];
		$this->roomnumber->AdvancedSearch->SearchCondition = @$filter["v_roomnumber"];
		$this->roomnumber->AdvancedSearch->SearchValue2 = @$filter["y_roomnumber"];
		$this->roomnumber->AdvancedSearch->SearchOperator2 = @$filter["w_roomnumber"];
		$this->roomnumber->AdvancedSearch->Save();

		// Field number
		$this->number->AdvancedSearch->SearchValue = @$filter["x_number"];
		$this->number->AdvancedSearch->SearchOperator = @$filter["z_number"];
		$this->number->AdvancedSearch->SearchCondition = @$filter["v_number"];
		$this->number->AdvancedSearch->SearchValue2 = @$filter["y_number"];
		$this->number->AdvancedSearch->SearchOperator2 = @$filter["w_number"];
		$this->number->AdvancedSearch->Save();

		// Field childs
		$this->childs->AdvancedSearch->SearchValue = @$filter["x_childs"];
		$this->childs->AdvancedSearch->SearchOperator = @$filter["z_childs"];
		$this->childs->AdvancedSearch->SearchCondition = @$filter["v_childs"];
		$this->childs->AdvancedSearch->SearchValue2 = @$filter["y_childs"];
		$this->childs->AdvancedSearch->SearchOperator2 = @$filter["w_childs"];
		$this->childs->AdvancedSearch->Save();

		// Field price
		$this->price->AdvancedSearch->SearchValue = @$filter["x_price"];
		$this->price->AdvancedSearch->SearchOperator = @$filter["z_price"];
		$this->price->AdvancedSearch->SearchCondition = @$filter["v_price"];
		$this->price->AdvancedSearch->SearchValue2 = @$filter["y_price"];
		$this->price->AdvancedSearch->SearchOperator2 = @$filter["w_price"];
		$this->price->AdvancedSearch->Save();

		// Field custom
		$this->custom->AdvancedSearch->SearchValue = @$filter["x_custom"];
		$this->custom->AdvancedSearch->SearchOperator = @$filter["z_custom"];
		$this->custom->AdvancedSearch->SearchCondition = @$filter["v_custom"];
		$this->custom->AdvancedSearch->SearchValue2 = @$filter["y_custom"];
		$this->custom->AdvancedSearch->SearchOperator2 = @$filter["w_custom"];
		$this->custom->AdvancedSearch->Save();

		// Field customp
		$this->customp->AdvancedSearch->SearchValue = @$filter["x_customp"];
		$this->customp->AdvancedSearch->SearchOperator = @$filter["z_customp"];
		$this->customp->AdvancedSearch->SearchCondition = @$filter["v_customp"];
		$this->customp->AdvancedSearch->SearchValue2 = @$filter["y_customp"];
		$this->customp->AdvancedSearch->SearchOperator2 = @$filter["w_customp"];
		$this->customp->AdvancedSearch->Save();

		// Field reservated
		$this->reservated->AdvancedSearch->SearchValue = @$filter["x_reservated"];
		$this->reservated->AdvancedSearch->SearchOperator = @$filter["z_reservated"];
		$this->reservated->AdvancedSearch->SearchCondition = @$filter["v_reservated"];
		$this->reservated->AdvancedSearch->SearchValue2 = @$filter["y_reservated"];
		$this->reservated->AdvancedSearch->SearchOperator2 = @$filter["w_reservated"];
		$this->reservated->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->country, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approve, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->room, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->roomnumber, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->price, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->custom, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->customp, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->arrival); // arrival
			$this->UpdateSort($this->departure); // departure
			$this->UpdateSort($this->user); // user
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->country); // country
			$this->UpdateSort($this->approve); // approve
			$this->UpdateSort($this->room); // room
			$this->UpdateSort($this->roomnumber); // roomnumber
			$this->UpdateSort($this->number); // number
			$this->UpdateSort($this->childs); // childs
			$this->UpdateSort($this->price); // price
			$this->UpdateSort($this->reservated); // reservated
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->arrival->setSort("");
				$this->departure->setSort("");
				$this->user->setSort("");
				$this->name->setSort("");
				$this->_email->setSort("");
				$this->country->setSort("");
				$this->approve->setSort("");
				$this->room->setSort("");
				$this->roomnumber->setSort("");
				$this->number->setSort("");
				$this->childs->setSort("");
				$this->price->setSort("");
				$this->reservated->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fwp_reservationslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fwp_reservationslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fwp_reservationslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fwp_reservationslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($wp_reservations_list)) $wp_reservations_list = new cwp_reservations_list();

// Page init
$wp_reservations_list->Page_Init();

// Page main
$wp_reservations_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$wp_reservations_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fwp_reservationslist = new ew_Form("fwp_reservationslist", "list");
fwp_reservationslist.FormKeyCountName = '<?php echo $wp_reservations_list->FormKeyCountName ?>';

// Form_CustomValidate event
fwp_reservationslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwp_reservationslist.ValidateRequired = true;
<?php } else { ?>
fwp_reservationslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fwp_reservationslistsrch = new ew_Form("fwp_reservationslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($wp_reservations_list->TotalRecs > 0 && $wp_reservations_list->ExportOptions->Visible()) { ?>
<?php $wp_reservations_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($wp_reservations_list->SearchOptions->Visible()) { ?>
<?php $wp_reservations_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($wp_reservations_list->FilterOptions->Visible()) { ?>
<?php $wp_reservations_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $wp_reservations_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($wp_reservations_list->TotalRecs <= 0)
			$wp_reservations_list->TotalRecs = $wp_reservations->SelectRecordCount();
	} else {
		if (!$wp_reservations_list->Recordset && ($wp_reservations_list->Recordset = $wp_reservations_list->LoadRecordset()))
			$wp_reservations_list->TotalRecs = $wp_reservations_list->Recordset->RecordCount();
	}
	$wp_reservations_list->StartRec = 1;
	if ($wp_reservations_list->DisplayRecs <= 0 || ($wp_reservations->Export <> "" && $wp_reservations->ExportAll)) // Display all records
		$wp_reservations_list->DisplayRecs = $wp_reservations_list->TotalRecs;
	if (!($wp_reservations->Export <> "" && $wp_reservations->ExportAll))
		$wp_reservations_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$wp_reservations_list->Recordset = $wp_reservations_list->LoadRecordset($wp_reservations_list->StartRec-1, $wp_reservations_list->DisplayRecs);

	// Set no record found message
	if ($wp_reservations->CurrentAction == "" && $wp_reservations_list->TotalRecs == 0) {
		if ($wp_reservations_list->SearchWhere == "0=101")
			$wp_reservations_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$wp_reservations_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$wp_reservations_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($wp_reservations->Export == "" && $wp_reservations->CurrentAction == "") { ?>
<form name="fwp_reservationslistsrch" id="fwp_reservationslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($wp_reservations_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fwp_reservationslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="wp_reservations">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($wp_reservations_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($wp_reservations_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $wp_reservations_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($wp_reservations_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($wp_reservations_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($wp_reservations_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($wp_reservations_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $wp_reservations_list->ShowPageHeader(); ?>
<?php
$wp_reservations_list->ShowMessage();
?>
<?php if ($wp_reservations_list->TotalRecs > 0 || $wp_reservations->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid wp_reservations">
<form name="fwp_reservationslist" id="fwp_reservationslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($wp_reservations_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $wp_reservations_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="wp_reservations">
<div id="gmp_wp_reservations" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($wp_reservations_list->TotalRecs > 0 || $wp_reservations->CurrentAction == "gridedit") { ?>
<table id="tbl_wp_reservationslist" class="table ewTable">
<?php echo $wp_reservations->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$wp_reservations_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$wp_reservations_list->RenderListOptions();

// Render list options (header, left)
$wp_reservations_list->ListOptions->Render("header", "left");
?>
<?php if ($wp_reservations->id->Visible) { // id ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->id) == "") { ?>
		<th data-name="id"><div id="elh_wp_reservations_id" class="wp_reservations_id"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->id) ?>',1);"><div id="elh_wp_reservations_id" class="wp_reservations_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->arrival->Visible) { // arrival ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->arrival) == "") { ?>
		<th data-name="arrival"><div id="elh_wp_reservations_arrival" class="wp_reservations_arrival"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->arrival->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="arrival"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->arrival) ?>',1);"><div id="elh_wp_reservations_arrival" class="wp_reservations_arrival">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->arrival->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->arrival->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->arrival->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->departure->Visible) { // departure ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->departure) == "") { ?>
		<th data-name="departure"><div id="elh_wp_reservations_departure" class="wp_reservations_departure"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->departure->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departure"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->departure) ?>',1);"><div id="elh_wp_reservations_departure" class="wp_reservations_departure">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->departure->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->departure->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->departure->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->user->Visible) { // user ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->user) == "") { ?>
		<th data-name="user"><div id="elh_wp_reservations_user" class="wp_reservations_user"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->user) ?>',1);"><div id="elh_wp_reservations_user" class="wp_reservations_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->user->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->name->Visible) { // name ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->name) == "") { ?>
		<th data-name="name"><div id="elh_wp_reservations_name" class="wp_reservations_name"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->name) ?>',1);"><div id="elh_wp_reservations_name" class="wp_reservations_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->_email->Visible) { // email ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->_email) == "") { ?>
		<th data-name="_email"><div id="elh_wp_reservations__email" class="wp_reservations__email"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->_email) ?>',1);"><div id="elh_wp_reservations__email" class="wp_reservations__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->country->Visible) { // country ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->country) == "") { ?>
		<th data-name="country"><div id="elh_wp_reservations_country" class="wp_reservations_country"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->country->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="country"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->country) ?>',1);"><div id="elh_wp_reservations_country" class="wp_reservations_country">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->country->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->country->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->country->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->approve->Visible) { // approve ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->approve) == "") { ?>
		<th data-name="approve"><div id="elh_wp_reservations_approve" class="wp_reservations_approve"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->approve->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="approve"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->approve) ?>',1);"><div id="elh_wp_reservations_approve" class="wp_reservations_approve">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->approve->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->approve->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->approve->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->room->Visible) { // room ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->room) == "") { ?>
		<th data-name="room"><div id="elh_wp_reservations_room" class="wp_reservations_room"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->room->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="room"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->room) ?>',1);"><div id="elh_wp_reservations_room" class="wp_reservations_room">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->room->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->room->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->room->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->roomnumber->Visible) { // roomnumber ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->roomnumber) == "") { ?>
		<th data-name="roomnumber"><div id="elh_wp_reservations_roomnumber" class="wp_reservations_roomnumber"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->roomnumber->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="roomnumber"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->roomnumber) ?>',1);"><div id="elh_wp_reservations_roomnumber" class="wp_reservations_roomnumber">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->roomnumber->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->roomnumber->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->roomnumber->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->number->Visible) { // number ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->number) == "") { ?>
		<th data-name="number"><div id="elh_wp_reservations_number" class="wp_reservations_number"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="number"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->number) ?>',1);"><div id="elh_wp_reservations_number" class="wp_reservations_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->number->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->childs->Visible) { // childs ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->childs) == "") { ?>
		<th data-name="childs"><div id="elh_wp_reservations_childs" class="wp_reservations_childs"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->childs->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="childs"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->childs) ?>',1);"><div id="elh_wp_reservations_childs" class="wp_reservations_childs">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->childs->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->childs->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->childs->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->price->Visible) { // price ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->price) == "") { ?>
		<th data-name="price"><div id="elh_wp_reservations_price" class="wp_reservations_price"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="price"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->price) ?>',1);"><div id="elh_wp_reservations_price" class="wp_reservations_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->price->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($wp_reservations->reservated->Visible) { // reservated ?>
	<?php if ($wp_reservations->SortUrl($wp_reservations->reservated) == "") { ?>
		<th data-name="reservated"><div id="elh_wp_reservations_reservated" class="wp_reservations_reservated"><div class="ewTableHeaderCaption"><?php echo $wp_reservations->reservated->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="reservated"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $wp_reservations->SortUrl($wp_reservations->reservated) ?>',1);"><div id="elh_wp_reservations_reservated" class="wp_reservations_reservated">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $wp_reservations->reservated->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($wp_reservations->reservated->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($wp_reservations->reservated->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$wp_reservations_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($wp_reservations->ExportAll && $wp_reservations->Export <> "") {
	$wp_reservations_list->StopRec = $wp_reservations_list->TotalRecs;
} else {

	// Set the last record to display
	if ($wp_reservations_list->TotalRecs > $wp_reservations_list->StartRec + $wp_reservations_list->DisplayRecs - 1)
		$wp_reservations_list->StopRec = $wp_reservations_list->StartRec + $wp_reservations_list->DisplayRecs - 1;
	else
		$wp_reservations_list->StopRec = $wp_reservations_list->TotalRecs;
}
$wp_reservations_list->RecCnt = $wp_reservations_list->StartRec - 1;
if ($wp_reservations_list->Recordset && !$wp_reservations_list->Recordset->EOF) {
	$wp_reservations_list->Recordset->MoveFirst();
	$bSelectLimit = $wp_reservations_list->UseSelectLimit;
	if (!$bSelectLimit && $wp_reservations_list->StartRec > 1)
		$wp_reservations_list->Recordset->Move($wp_reservations_list->StartRec - 1);
} elseif (!$wp_reservations->AllowAddDeleteRow && $wp_reservations_list->StopRec == 0) {
	$wp_reservations_list->StopRec = $wp_reservations->GridAddRowCount;
}

// Initialize aggregate
$wp_reservations->RowType = EW_ROWTYPE_AGGREGATEINIT;
$wp_reservations->ResetAttrs();
$wp_reservations_list->RenderRow();
while ($wp_reservations_list->RecCnt < $wp_reservations_list->StopRec) {
	$wp_reservations_list->RecCnt++;
	if (intval($wp_reservations_list->RecCnt) >= intval($wp_reservations_list->StartRec)) {
		$wp_reservations_list->RowCnt++;

		// Set up key count
		$wp_reservations_list->KeyCount = $wp_reservations_list->RowIndex;

		// Init row class and style
		$wp_reservations->ResetAttrs();
		$wp_reservations->CssClass = "";
		if ($wp_reservations->CurrentAction == "gridadd") {
		} else {
			$wp_reservations_list->LoadRowValues($wp_reservations_list->Recordset); // Load row values
		}
		$wp_reservations->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$wp_reservations->RowAttrs = array_merge($wp_reservations->RowAttrs, array('data-rowindex'=>$wp_reservations_list->RowCnt, 'id'=>'r' . $wp_reservations_list->RowCnt . '_wp_reservations', 'data-rowtype'=>$wp_reservations->RowType));

		// Render row
		$wp_reservations_list->RenderRow();

		// Render list options
		$wp_reservations_list->RenderListOptions();
?>
	<tr<?php echo $wp_reservations->RowAttributes() ?>>
<?php

// Render list options (body, left)
$wp_reservations_list->ListOptions->Render("body", "left", $wp_reservations_list->RowCnt);
?>
	<?php if ($wp_reservations->id->Visible) { // id ?>
		<td data-name="id"<?php echo $wp_reservations->id->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_id" class="wp_reservations_id">
<span<?php echo $wp_reservations->id->ViewAttributes() ?>>
<?php echo $wp_reservations->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $wp_reservations_list->PageObjName . "_row_" . $wp_reservations_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($wp_reservations->arrival->Visible) { // arrival ?>
		<td data-name="arrival"<?php echo $wp_reservations->arrival->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_arrival" class="wp_reservations_arrival">
<span<?php echo $wp_reservations->arrival->ViewAttributes() ?>>
<?php echo $wp_reservations->arrival->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->departure->Visible) { // departure ?>
		<td data-name="departure"<?php echo $wp_reservations->departure->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_departure" class="wp_reservations_departure">
<span<?php echo $wp_reservations->departure->ViewAttributes() ?>>
<?php echo $wp_reservations->departure->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->user->Visible) { // user ?>
		<td data-name="user"<?php echo $wp_reservations->user->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_user" class="wp_reservations_user">
<span<?php echo $wp_reservations->user->ViewAttributes() ?>>
<?php echo $wp_reservations->user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->name->Visible) { // name ?>
		<td data-name="name"<?php echo $wp_reservations->name->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_name" class="wp_reservations_name">
<span<?php echo $wp_reservations->name->ViewAttributes() ?>>
<?php echo $wp_reservations->name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $wp_reservations->_email->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations__email" class="wp_reservations__email">
<span<?php echo $wp_reservations->_email->ViewAttributes() ?>>
<?php echo $wp_reservations->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->country->Visible) { // country ?>
		<td data-name="country"<?php echo $wp_reservations->country->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_country" class="wp_reservations_country">
<span<?php echo $wp_reservations->country->ViewAttributes() ?>>
<?php echo $wp_reservations->country->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->approve->Visible) { // approve ?>
		<td data-name="approve"<?php echo $wp_reservations->approve->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_approve" class="wp_reservations_approve">
<span<?php echo $wp_reservations->approve->ViewAttributes() ?>>
<?php echo $wp_reservations->approve->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->room->Visible) { // room ?>
		<td data-name="room"<?php echo $wp_reservations->room->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_room" class="wp_reservations_room">
<span<?php echo $wp_reservations->room->ViewAttributes() ?>>
<?php echo $wp_reservations->room->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->roomnumber->Visible) { // roomnumber ?>
		<td data-name="roomnumber"<?php echo $wp_reservations->roomnumber->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_roomnumber" class="wp_reservations_roomnumber">
<span<?php echo $wp_reservations->roomnumber->ViewAttributes() ?>>
<?php echo $wp_reservations->roomnumber->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->number->Visible) { // number ?>
		<td data-name="number"<?php echo $wp_reservations->number->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_number" class="wp_reservations_number">
<span<?php echo $wp_reservations->number->ViewAttributes() ?>>
<?php echo $wp_reservations->number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->childs->Visible) { // childs ?>
		<td data-name="childs"<?php echo $wp_reservations->childs->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_childs" class="wp_reservations_childs">
<span<?php echo $wp_reservations->childs->ViewAttributes() ?>>
<?php echo $wp_reservations->childs->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->price->Visible) { // price ?>
		<td data-name="price"<?php echo $wp_reservations->price->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_price" class="wp_reservations_price">
<span<?php echo $wp_reservations->price->ViewAttributes() ?>>
<?php echo $wp_reservations->price->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($wp_reservations->reservated->Visible) { // reservated ?>
		<td data-name="reservated"<?php echo $wp_reservations->reservated->CellAttributes() ?>>
<span id="el<?php echo $wp_reservations_list->RowCnt ?>_wp_reservations_reservated" class="wp_reservations_reservated">
<span<?php echo $wp_reservations->reservated->ViewAttributes() ?>>
<?php echo $wp_reservations->reservated->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$wp_reservations_list->ListOptions->Render("body", "right", $wp_reservations_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($wp_reservations->CurrentAction <> "gridadd")
		$wp_reservations_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($wp_reservations->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($wp_reservations_list->Recordset)
	$wp_reservations_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($wp_reservations->CurrentAction <> "gridadd" && $wp_reservations->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($wp_reservations_list->Pager)) $wp_reservations_list->Pager = new cPrevNextPager($wp_reservations_list->StartRec, $wp_reservations_list->DisplayRecs, $wp_reservations_list->TotalRecs) ?>
<?php if ($wp_reservations_list->Pager->RecordCount > 0 && $wp_reservations_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($wp_reservations_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $wp_reservations_list->PageUrl() ?>start=<?php echo $wp_reservations_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($wp_reservations_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $wp_reservations_list->PageUrl() ?>start=<?php echo $wp_reservations_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $wp_reservations_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($wp_reservations_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $wp_reservations_list->PageUrl() ?>start=<?php echo $wp_reservations_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($wp_reservations_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $wp_reservations_list->PageUrl() ?>start=<?php echo $wp_reservations_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $wp_reservations_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $wp_reservations_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $wp_reservations_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $wp_reservations_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($wp_reservations_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($wp_reservations_list->TotalRecs == 0 && $wp_reservations->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($wp_reservations_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fwp_reservationslistsrch.FilterList = <?php echo $wp_reservations_list->GetFilterList() ?>;
fwp_reservationslistsrch.Init();
fwp_reservationslist.Init();
</script>
<?php
$wp_reservations_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$wp_reservations_list->Page_Terminate();
?>
