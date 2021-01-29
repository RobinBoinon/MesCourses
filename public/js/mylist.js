function ShowLoading() {
  $("#screenShadowLoading").show();
  $("#Loading").show();
}

function HideLoading() {
  $("#screenShadowLoading").hide();
  $("#Loading").hide();
}

function showPopupNewList() {
  $("#PopupTextSpan").html("Voulez-vous vraiment commencer une nouvelle liste ?");
  $("#PopupContentDiv").html("<input type=\"button\" class=\"otherbutton\" value=\"Annuler\" onclick=\"HidePopup()\"><input type=\"button\" class=\"otherbutton\" value=\"Confirmer\" onclick=\"CreateNewList()\">");
  showPopup(150);
}

function showPopup(height) {
  $("#Popup").height(height);
  $("#screenShadow").show();
  $("#Popup").show();
}

function HidePopup() {
  $("#screenShadow").hide();
  $("#Popup").hide();
}

function CreateNewList() {
  location.href = '/newlist';
}

function quantityMinus(productId) {
  ShowLoading();
  $.ajax({
    url: '/quantityminus',
    type: 'GET',
    data: "productId="+productId,
    datatype: 'json'
  })
  .done(function (data) {
    if(data.newquantity <= 0){
      location.href = '/maliste';
    }
    else{
      $("#quantityNumber_"+productId).html(data.newquantity);
    }
    HideLoading();
  })
  .fail(function (jqXHR, textStatus, errorThrown) {});
}

function quantityPlus(productId) {
  ShowLoading();
  $.ajax({
    url: '/quantityplus',
    type: 'GET',
    data: "productId="+productId,
    datatype: 'json'
  })
  .done(function (data) {
    $("#quantityNumber_"+productId).html(data.newquantity);
    HideLoading();
  })
  .fail(function (jqXHR, textStatus, errorThrown) {});
}

function deleteProductFromCurrentList(productId) {
  ShowLoading();
  $.ajax({
    url: '/deleteproductfromcurrentlist',
    type: 'GET',
    data: "productId="+productId,
    datatype: 'json'
  })
  .done(function (data) {
    location.href = '/maliste';
  })
  .fail(function (jqXHR, textStatus, errorThrown) {});
}

function LoadPopUpImage(productId,productName) {
  ShowLoading();
  var imageNumber = 3;
  $.ajax({
    url: '/findgoogleimage',
    type: 'GET',
    data: "productName="+productName,
    datatype: 'json'
  })
  .done(function (data) {
    $("#PopupTextSpan").html("Choisissez une image pour le produit : "+productName);
    var html = "<div class=\"googleImageContainer\">";
    for ($i = 2; $i <= imageNumber+1; $i++) {
        html += "<img onclick=\"DefineImageForProduct("+productId+",\'"+data[$i]+"\')\" src=\""+data[$i]+"\"/>";
    }
    html += "</div>";
    html += "<input type=\"button\" class=\"otherbutton\" value=\"Annuler\" onclick=\"HidePopup()\">";
    $("#PopupContentDiv").html(html);
    showPopup(220);
    HideLoading();
  })
  .fail(function (jqXHR, textStatus, errorThrown) {});
}

function DefineImageForProduct(productId,imageLink) {
  console.log("trucS");
  ShowLoading();
  $.ajax({
    url: '/defineimageforproduct',
    type: 'GET',
    data: "productId="+productId+'&imageLink='+imageLink,
    datatype: 'json'
  })
  .done(function (data) {
    location.href = '/maliste';
  })
  .fail(function (jqXHR, textStatus, errorThrown) {});
}
