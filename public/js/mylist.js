function showPopupNewList() {
  $("#PopupYesNoTextSpan").html("Voulez-vous vraiment commencer une nouvelle liste ?");
  $("#screenShadow").show();
  $("#PopupYesNo").show();
}

function HidePopupNewList() {
  $("#screenShadow").hide();
  $("#PopupYesNo").hide();
}

function CreateNewList() {
  console.log("CreateNewList");
  location.href = '/newlist';
}
