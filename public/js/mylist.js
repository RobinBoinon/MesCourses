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

function AddProductToList(productId) {
  ShowLoading();
  $.ajax({
    url: '/addproducttolist',
    type: 'GET',
    data: "productId="+productId,
    datatype: 'json'
  })
  .done(function (data) {
    location.href = '/maliste';
  })
  .fail(function (jqXHR, textStatus, errorThrown) {});
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

function AutocompleteProduct() {
  var search = $("#form_name").val().toLowerCase();
  var match = [];
  if(search.length > 2){ //Autocomplete only if the string is 3 character or more
    $("#autocompleteresult").html("");
    allproduct.forEach(function(product){
      if((product['name'].toLowerCase()).includes(search)){
        match.push(product);
      }
    });
    var space = 2;
    var newtop = space + $("#form_name").position().top + $("#form_name").height() + parseInt($("#form_name").css("border-top-width")) + parseInt($("#form_name").css("border-bottom-width")) + parseInt($("#form_name").css("marginTop")) + parseInt($("#form_name").css("marginBottom")) + parseInt($("#form_name").css("paddingTop")) + parseInt($("#form_name").css("paddingBottom"));
    var newleft = $("#form_name").position().left + parseInt($("#form_name").css("marginLeft"));
    $("#autocompleteresult").css('top', newtop + 'px');
    $("#autocompleteresult").css('left', newleft + 'px');

    //remplir la div de résultat -> id image nom + onclick->ajout à la liste refresh
    var i = 1;
    var mycontent = "";
    match.forEach(function(product){
      var rowclass = "oddrow";
      if(i % 2 == 0){
        rowclass = "evenrow";
      }
      if(i > 1){
        rowclass += " rowBorderTop";
      }
      mycontent += "<div class=\"autocompleteresultRow "+rowclass+"\" onclick=\"AddProductToList("+product['id']+")\">";
      mycontent += "<div class=\"autocompleteresultRowDivImage\">";
      if(product['imagelink'] === null){
        mycontent += "<div class=\"autocompleteresultRowImage\"><i class=\"fas fa-question autocompleteresultRowImageFA\"></i></div>";
      }
      else{
        mycontent += "<img class=\"autocompleteresultRowImage\" src=\""+product['imagelink']+"\"/>";
      }
      mycontent += "</div>";
      mycontent += "<div class=\"autocompleteresultRowName\">"+product['name']+"</div>";
      mycontent += "</div>";
      i++;
    });

    if(mycontent != ""){
      $("#autocompleteresult").append(mycontent);
      $("#autocompleteresult").show();
    }
  }
  else{
    $("#autocompleteresult").hide();
  }
}

//Hide autocomplete div when clicked elsewhere
$(document).mouseup(function(e){
    var container = $("#autocompleteresult");
    if(!container.is(e.target) && container.has(e.target).length === 0){
        container.hide();
    }
});
