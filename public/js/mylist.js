function ShowLoading() {
  $("#screenShadowLoading").show();
  $("#Loading").show();
}

function HideLoading() {
  $("#screenShadowLoading").hide();
  $("#Loading").hide();
}

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

function LoadPopUpImage() {
  console.log("LoadPopUpImage");
}
