function showAddCompanyForm() {
  document.getElementById("add_company_form").style.display = "block";
}
function hideAddCompanyForm() {
  document.getElementById("add_company_form").style.display = "none";
}
$("#add_company_form").submit(function(event) {
  event.preventDefault();
  var form = $(this);
  var cmp = {
    name: $("input[name=cmp_name]").val(),
    address: $("input[name=cmp_address]").val(),
    phone: $("input[name=cmp_phone]").val()
  };
  console.log(cmp);
  $.ajax({
    url: "http://localhost:8000/companies/create",
    type: "post",
    data: JSON.stringify(cmp),
    dataType: "json",
    encode: true,
    async: true,
    contentType: "application/json",
    success: function(response) {
      hideAddCompanyForm();
      refreshCompaniesList(response);
    }
  });
});

function refreshCompaniesList(response) {
  $.ajax({
    url: "http://localhost:8000/companies/",
    type: "get",
    async: true
  }).done(function(data) {
    $("#companiesListDiv").html(data);
  });
}
function removeCompany(cmpId) {
  console.log("Removing company with id" + cmpId);
  $.ajax({
    url: "http://localhost:8000/companies/remove/" + cmpId,
    type: "delete",
    async: true
  }).done(function(response) {
    refreshCompaniesList(response);
  });
}
