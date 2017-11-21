<?php

// Global variable for table object
$wp_reservations = NULL;

//
// Table class for wp_reservations
//
class cwp_reservations extends cTable {
	var $id;
	var $arrival;
	var $departure;
	var $user;
	var $name;
	var $_email;
	var $country;
	var $approve;
	var $room;
	var $roomnumber;
	var $number;
	var $childs;
	var $price;
	var $custom;
	var $customp;
	var $reservated;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'wp_reservations';
		$this->TableName = 'wp_reservations';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`wp_reservations`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('wp_reservations', 'wp_reservations', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// arrival
		$this->arrival = new cField('wp_reservations', 'wp_reservations', 'x_arrival', 'arrival', '`arrival`', ew_CastDateFieldForLike('`arrival`', 0, "DB"), 135, 0, FALSE, '`arrival`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->arrival->Sortable = TRUE; // Allow sort
		$this->arrival->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['arrival'] = &$this->arrival;

		// departure
		$this->departure = new cField('wp_reservations', 'wp_reservations', 'x_departure', 'departure', '`departure`', ew_CastDateFieldForLike('`departure`', 0, "DB"), 135, 0, FALSE, '`departure`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->departure->Sortable = TRUE; // Allow sort
		$this->departure->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['departure'] = &$this->departure;

		// user
		$this->user = new cField('wp_reservations', 'wp_reservations', 'x_user', 'user', '`user`', '`user`', 3, -1, FALSE, '`user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->user->Sortable = TRUE; // Allow sort
		$this->user->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user'] = &$this->user;

		// name
		$this->name = new cField('wp_reservations', 'wp_reservations', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->fields['name'] = &$this->name;

		// email
		$this->_email = new cField('wp_reservations', 'wp_reservations', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// country
		$this->country = new cField('wp_reservations', 'wp_reservations', 'x_country', 'country', '`country`', '`country`', 200, -1, FALSE, '`country`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->country->Sortable = TRUE; // Allow sort
		$this->fields['country'] = &$this->country;

		// approve
		$this->approve = new cField('wp_reservations', 'wp_reservations', 'x_approve', 'approve', '`approve`', '`approve`', 200, -1, FALSE, '`approve`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approve->Sortable = TRUE; // Allow sort
		$this->fields['approve'] = &$this->approve;

		// room
		$this->room = new cField('wp_reservations', 'wp_reservations', 'x_room', 'room', '`room`', '`room`', 200, -1, FALSE, '`room`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->room->Sortable = TRUE; // Allow sort
		$this->fields['room'] = &$this->room;

		// roomnumber
		$this->roomnumber = new cField('wp_reservations', 'wp_reservations', 'x_roomnumber', 'roomnumber', '`roomnumber`', '`roomnumber`', 200, -1, FALSE, '`roomnumber`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->roomnumber->Sortable = TRUE; // Allow sort
		$this->fields['roomnumber'] = &$this->roomnumber;

		// number
		$this->number = new cField('wp_reservations', 'wp_reservations', 'x_number', 'number', '`number`', '`number`', 3, -1, FALSE, '`number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->number->Sortable = TRUE; // Allow sort
		$this->number->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['number'] = &$this->number;

		// childs
		$this->childs = new cField('wp_reservations', 'wp_reservations', 'x_childs', 'childs', '`childs`', '`childs`', 3, -1, FALSE, '`childs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->childs->Sortable = TRUE; // Allow sort
		$this->childs->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['childs'] = &$this->childs;

		// price
		$this->price = new cField('wp_reservations', 'wp_reservations', 'x_price', 'price', '`price`', '`price`', 200, -1, FALSE, '`price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->price->Sortable = TRUE; // Allow sort
		$this->fields['price'] = &$this->price;

		// custom
		$this->custom = new cField('wp_reservations', 'wp_reservations', 'x_custom', 'custom', '`custom`', '`custom`', 201, -1, FALSE, '`custom`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->custom->Sortable = TRUE; // Allow sort
		$this->fields['custom'] = &$this->custom;

		// customp
		$this->customp = new cField('wp_reservations', 'wp_reservations', 'x_customp', 'customp', '`customp`', '`customp`', 201, -1, FALSE, '`customp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->customp->Sortable = TRUE; // Allow sort
		$this->fields['customp'] = &$this->customp;

		// reservated
		$this->reservated = new cField('wp_reservations', 'wp_reservations', 'x_reservated', 'reservated', '`reservated`', ew_CastDateFieldForLike('`reservated`', 0, "DB"), 135, 0, FALSE, '`reservated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reservated->Sortable = TRUE; // Allow sort
		$this->reservated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['reservated'] = &$this->reservated;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`wp_reservations`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "wp_reservationslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "wp_reservationslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("wp_reservationsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("wp_reservationsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "wp_reservationsadd.php?" . $this->UrlParm($parm);
		else
			$url = "wp_reservationsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("wp_reservationsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("wp_reservationsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("wp_reservationsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKeys[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// arrival
		$this->arrival->EditAttrs["class"] = "form-control";
		$this->arrival->EditCustomAttributes = "";
		$this->arrival->EditValue = ew_FormatDateTime($this->arrival->CurrentValue, 8);
		$this->arrival->PlaceHolder = ew_RemoveHtml($this->arrival->FldCaption());

		// departure
		$this->departure->EditAttrs["class"] = "form-control";
		$this->departure->EditCustomAttributes = "";
		$this->departure->EditValue = ew_FormatDateTime($this->departure->CurrentValue, 8);
		$this->departure->PlaceHolder = ew_RemoveHtml($this->departure->FldCaption());

		// user
		$this->user->EditAttrs["class"] = "form-control";
		$this->user->EditCustomAttributes = "";
		$this->user->EditValue = $this->user->CurrentValue;
		$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";
		$this->name->EditValue = $this->name->CurrentValue;
		$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// country
		$this->country->EditAttrs["class"] = "form-control";
		$this->country->EditCustomAttributes = "";
		$this->country->EditValue = $this->country->CurrentValue;
		$this->country->PlaceHolder = ew_RemoveHtml($this->country->FldCaption());

		// approve
		$this->approve->EditAttrs["class"] = "form-control";
		$this->approve->EditCustomAttributes = "";
		$this->approve->EditValue = $this->approve->CurrentValue;
		$this->approve->PlaceHolder = ew_RemoveHtml($this->approve->FldCaption());

		// room
		$this->room->EditAttrs["class"] = "form-control";
		$this->room->EditCustomAttributes = "";
		$this->room->EditValue = $this->room->CurrentValue;
		$this->room->PlaceHolder = ew_RemoveHtml($this->room->FldCaption());

		// roomnumber
		$this->roomnumber->EditAttrs["class"] = "form-control";
		$this->roomnumber->EditCustomAttributes = "";
		$this->roomnumber->EditValue = $this->roomnumber->CurrentValue;
		$this->roomnumber->PlaceHolder = ew_RemoveHtml($this->roomnumber->FldCaption());

		// number
		$this->number->EditAttrs["class"] = "form-control";
		$this->number->EditCustomAttributes = "";
		$this->number->EditValue = $this->number->CurrentValue;
		$this->number->PlaceHolder = ew_RemoveHtml($this->number->FldCaption());

		// childs
		$this->childs->EditAttrs["class"] = "form-control";
		$this->childs->EditCustomAttributes = "";
		$this->childs->EditValue = $this->childs->CurrentValue;
		$this->childs->PlaceHolder = ew_RemoveHtml($this->childs->FldCaption());

		// price
		$this->price->EditAttrs["class"] = "form-control";
		$this->price->EditCustomAttributes = "";
		$this->price->EditValue = $this->price->CurrentValue;
		$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());

		// custom
		$this->custom->EditAttrs["class"] = "form-control";
		$this->custom->EditCustomAttributes = "";
		$this->custom->EditValue = $this->custom->CurrentValue;
		$this->custom->PlaceHolder = ew_RemoveHtml($this->custom->FldCaption());

		// customp
		$this->customp->EditAttrs["class"] = "form-control";
		$this->customp->EditCustomAttributes = "";
		$this->customp->EditValue = $this->customp->CurrentValue;
		$this->customp->PlaceHolder = ew_RemoveHtml($this->customp->FldCaption());

		// reservated
		$this->reservated->EditAttrs["class"] = "form-control";
		$this->reservated->EditCustomAttributes = "";
		$this->reservated->EditValue = ew_FormatDateTime($this->reservated->CurrentValue, 8);
		$this->reservated->PlaceHolder = ew_RemoveHtml($this->reservated->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->arrival->Exportable) $Doc->ExportCaption($this->arrival);
					if ($this->departure->Exportable) $Doc->ExportCaption($this->departure);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->country->Exportable) $Doc->ExportCaption($this->country);
					if ($this->approve->Exportable) $Doc->ExportCaption($this->approve);
					if ($this->room->Exportable) $Doc->ExportCaption($this->room);
					if ($this->roomnumber->Exportable) $Doc->ExportCaption($this->roomnumber);
					if ($this->number->Exportable) $Doc->ExportCaption($this->number);
					if ($this->childs->Exportable) $Doc->ExportCaption($this->childs);
					if ($this->price->Exportable) $Doc->ExportCaption($this->price);
					if ($this->custom->Exportable) $Doc->ExportCaption($this->custom);
					if ($this->customp->Exportable) $Doc->ExportCaption($this->customp);
					if ($this->reservated->Exportable) $Doc->ExportCaption($this->reservated);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->arrival->Exportable) $Doc->ExportCaption($this->arrival);
					if ($this->departure->Exportable) $Doc->ExportCaption($this->departure);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->country->Exportable) $Doc->ExportCaption($this->country);
					if ($this->approve->Exportable) $Doc->ExportCaption($this->approve);
					if ($this->room->Exportable) $Doc->ExportCaption($this->room);
					if ($this->roomnumber->Exportable) $Doc->ExportCaption($this->roomnumber);
					if ($this->number->Exportable) $Doc->ExportCaption($this->number);
					if ($this->childs->Exportable) $Doc->ExportCaption($this->childs);
					if ($this->price->Exportable) $Doc->ExportCaption($this->price);
					if ($this->reservated->Exportable) $Doc->ExportCaption($this->reservated);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->arrival->Exportable) $Doc->ExportField($this->arrival);
						if ($this->departure->Exportable) $Doc->ExportField($this->departure);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->country->Exportable) $Doc->ExportField($this->country);
						if ($this->approve->Exportable) $Doc->ExportField($this->approve);
						if ($this->room->Exportable) $Doc->ExportField($this->room);
						if ($this->roomnumber->Exportable) $Doc->ExportField($this->roomnumber);
						if ($this->number->Exportable) $Doc->ExportField($this->number);
						if ($this->childs->Exportable) $Doc->ExportField($this->childs);
						if ($this->price->Exportable) $Doc->ExportField($this->price);
						if ($this->custom->Exportable) $Doc->ExportField($this->custom);
						if ($this->customp->Exportable) $Doc->ExportField($this->customp);
						if ($this->reservated->Exportable) $Doc->ExportField($this->reservated);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->arrival->Exportable) $Doc->ExportField($this->arrival);
						if ($this->departure->Exportable) $Doc->ExportField($this->departure);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->country->Exportable) $Doc->ExportField($this->country);
						if ($this->approve->Exportable) $Doc->ExportField($this->approve);
						if ($this->room->Exportable) $Doc->ExportField($this->room);
						if ($this->roomnumber->Exportable) $Doc->ExportField($this->roomnumber);
						if ($this->number->Exportable) $Doc->ExportField($this->number);
						if ($this->childs->Exportable) $Doc->ExportField($this->childs);
						if ($this->price->Exportable) $Doc->ExportField($this->price);
						if ($this->reservated->Exportable) $Doc->ExportField($this->reservated);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
