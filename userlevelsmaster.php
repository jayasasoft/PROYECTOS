<?php

// userlevelid
// userlevelname

?>
<?php if ($userlevels->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $userlevels->TableCaption() ?></h4> -->
<table id="tbl_userlevelsmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $userlevels->TableCustomInnerHtml ?>
	<tbody>
<?php if ($userlevels->userlevelid->Visible) { // userlevelid ?>
		<tr id="r_userlevelid">
			<td><?php echo $userlevels->userlevelid->FldCaption() ?></td>
			<td<?php echo $userlevels->userlevelid->CellAttributes() ?>>
<span id="el_userlevels_userlevelid">
<span<?php echo $userlevels->userlevelid->ViewAttributes() ?>>
<?php echo $userlevels->userlevelid->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($userlevels->userlevelname->Visible) { // userlevelname ?>
		<tr id="r_userlevelname">
			<td><?php echo $userlevels->userlevelname->FldCaption() ?></td>
			<td<?php echo $userlevels->userlevelname->CellAttributes() ?>>
<span id="el_userlevels_userlevelname">
<span<?php echo $userlevels->userlevelname->ViewAttributes() ?>>
<?php echo $userlevels->userlevelname->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
