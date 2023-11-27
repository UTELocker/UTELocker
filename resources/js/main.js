var accItem = document.getElementsByClassName('accordionItem');
var accHD = document.getElementsByClassName('accordionItemHeading');
for (i = 0; i < accHD.length; i++) {
    accHD[i].addEventListener('click', toggleItem, false);
}

function toggleItem() {
    var itemClass = this.parentNode.className;

    for (i = 0; i < accItem.length; i++) {
        accItem[i].className = 'accordionItem closeIt';
    }
    if (itemClass == 'accordionItem closeIt') {
        this.parentNode.className = 'accordionItem openIt';
    }
}

var ts = document.getElementById('sidebarToggle');
if (ts) {
    ts.addEventListener("click", toggleSidebar);
}

function toggleSidebar() {
    let toggle = document.querySelector('body');
    toggle.classList.toggle('sidebar-toggled');
}

window.addEventListener("resize", resiz);
function resiz() {
    if (screen.width < 769) {
        var element = document.querySelector("body");
        element.classList.remove("sidebar-toggled");
    }
}

function openMoreFilter() {
    var omf = document.getElementById("more_filter");
    omf.classList.add("in");
}

function closeMoreFilter() {
    var cls = document.getElementById("more_filter");
    cls.classList.remove("in");
}

if ($('#more_filter').length > 0) {
    $(document).on('mouseup', function(e)
    {
        var container = $("#more_filter");
        var searchField = $(".bs-searchbox");
        var select2Field = $("#bs-select-2");
        var selectField = $(".bs-container");

        if (!container.is(e.target) && container.has(e.target).length === 0 && !searchField.is(e.target) && searchField.has(e.target).length === 0 && !select2Field.is(e.target) && select2Field.has(e.target).length === 0 && selectField.has(e.target).length === 0)
        {
            closeMoreFilter()
        }
    });
}

function openMobileMenu() {
    var omm = document.getElementById("mobile_menu_collapse");
    omm.classList.add("toggled");

    var omm1 = document.getElementById("mobile_close_panel");
    omm1.classList.add("toggled");
}

function closeMobileMenu() {
    var cmm = document.getElementById("mobile_menu_collapse");
    cmm.classList.remove("toggled");

    var cmm1 = document.getElementById("mobile_close_panel");
    cmm1.classList.remove("toggled");
}

function openAdminDashboard() {
    var oad1 = document.getElementById("mob-admin-dash");
    oad1.classList.add("in");

    var oad2 = document.getElementById("close-admin-overlay");
    oad2.classList.add("in");
}

var el = document.getElementById('close-admin-overlay');
if (el) {
    el.addEventListener("click", closeAdminDashboard);
}

var el = document.getElementById('close-admin');
if (el) {
    el.addEventListener("click", closeAdminDashboard);
}

function closeAdminDashboard() {
    var cad1 = document.getElementById("mob-admin-dash");
    cad1.classList.remove("in");

    var cad2 = document.getElementById("close-admin-overlay");
    cad2.classList.remove("in");
}

function openSettingsSidebar() {
    var oss1 = document.getElementById("mob-settings-sidebar");
    oss1.classList.add("in");

    var oss2 = document.getElementById("close-settings-overlay");
    oss2.classList.add("in");
}

var el = document.getElementById('close-settings');
if (el) {
    el.addEventListener("click", closeSettingsSidebar);
}

var el = document.getElementById('close-settings-overlay');
if (el) {
    el.addEventListener("click", closeSettingsSidebar);
}

function closeSettingsSidebar() {
    var cls1 = document.getElementById("mob-settings-sidebar");
    cls1.classList.remove("in");

    var cls2 = document.getElementById("close-settings-overlay");
    cls2.classList.remove("in");
}

function openTicketsSidebar() {
    var ots1 = document.getElementById("ticket-detail-contact");
    ots1.classList.add("in");

    var oss2 = document.getElementById("close-tickets-overlay");
    oss2.classList.add("in");
}

var el = document.getElementById('close-tickets');
if (el) {
    el.addEventListener("click", closeTicketsSidebar);
}

var el = document.getElementById('close-tickets-overlay');
if (el) {
    el.addEventListener("click", closeTicketsSidebar);
}

function closeTicketsSidebar(){
    var cts1 = document.getElementById("ticket-detail-contact");
    cts1.classList.remove("in");

    var cts2 = document.getElementById("close-tickets-overlay");
    cts2.classList.remove("in");
}

function openClientDetailSidebar() {
    var ocds1 = document.getElementById("mob-client-detail");
    ocds1.classList.add("in");

    var ocds2 = document.getElementById("close-client-overlay");
    ocds2.classList.add("in");

    // var ocds4 = document.getElementById("close-client-detail");
    // ocds4.classList.remove("d-none");

    var ocds3 = document.getElementById("hide-project-menues");
    ocds3.classList.add("in");
}

var el = document.getElementById('close-client-overlay');
if (el) {
    el.addEventListener("click", closeClientDetail);
}

var el = document.getElementById('close-client-detail');
if (el) {
    el.addEventListener("click", closeClientDetail);
}

function closeClientDetail() {
    // var ocds4 = document.getElementById("close-client-detail");
    // ocds4.classList.add("d-none");

    var ccd1 = document.getElementById("mob-client-detail");
    ccd1.classList.remove("in");

    var ccd2 = document.getElementById("close-client-overlay");
    ccd2.classList.remove("in");

    var ccd3 = document.getElementById("hide-project-menues");
    ccd3.classList.remove("in");
}

function openProjectSidebar() {
    var ops1 = document.getElementById("mob-project-menu");
    ops1.classList.add("in");

    var ops2 = document.getElementById("close-project-overlay");
    ops2.classList.add("in");
}

var el = document.getElementById('close-project-overlay');
if (el) {
    el.addEventListener("click", closeProjectSidebar);
}

var el = document.getElementById('close-projects');
if (el) {
    el.addEventListener("click", closeProjectSidebar);
}

function closeProjectSidebar() {
    var cps1 = document.getElementById("mob-project-menu");
    cps1.classList.remove("in");

    var cps2 = document.getElementById("close-project-overlay");
    cps2.classList.remove("in");
}

function msgTabs(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    document.getElementById('msgContentRight').className += ' d-block';
}

function closeMessageTab() {
    var cmt = document.getElementById("msgContentRight");
    cmt.classList.remove("d-block");
}

function rtl() {
    var rtl = document.querySelector("body");
    rtl.classList.toggle("rtl");
}

function openTaskDetail() {
    var otd1 = document.getElementById("task-detail-1");
    otd1.classList.add("in");

    var ops2 = document.getElementById("close-task-detail-overlay");
    ops2.classList.add("in");

    var otd4 = document.getElementById("close-task-detail");
    otd4.classList.add("in");
}

var el = document.getElementById('close-task-detail-overlay');
if (el) {
    el.addEventListener("click", closeTaskDetail);
}

var el = document.getElementById('close-task-detail');
if (el) {
    el.addEventListener("click", closeTaskDetail);
}

function closeTaskDetail() {
    var ctd1 = document.getElementById("task-detail-1");
    ctd1.classList.remove("in");

    var ctd2 = document.getElementById("close-task-detail-overlay");
    ctd2.classList.remove("in");

    sessionStorage.setItem('RIGHT_MODAL', 'opened');

    window.history.back();

    var ctd3 = document.getElementById("close-task-detail");
    ctd3.classList.remove("in");
}

var el = document.getElementById("close-task-detail");

$("body").on("click", ".openRightModal", openTaskDetail);

var el = document.querySelector(".closeRightModal");
if (el) {
    el.addEventListener("click", closeTaskDetail);
}

$(".toggle-password").click(function () {
    var $selector = $(this).closest(".input-group").find("input.form-control");
    $(this).find(".svg-inline--fa").toggleClass("fa-eye fa-eye-slash");
    var $type = $selector.attr("type") === "password" ? "text" : "password";
    $selector.attr("type", $type);
});

$("body").on("click", ".openRightModal", function (event) {
    event.preventDefault();

    const requestUrl = this.href;
    const inModal = $(this).hasClass("inModal");

    let redirectUrl = "";
    if (typeof $(this).data("redirect-url") !== "undefined") {
        redirectUrl = encodeURIComponent($(this).data("redirect-url"));
    }

    $.easyAjax({
        url: requestUrl,
        blockUI: true,
        container: RIGHT_MODAL,
        historyPush: !inModal,
        data: { redirectUrl: redirectUrl },
        success: function (response) {
            if (response.status == "success") {
                $(RIGHT_MODAL_CONTENT).html(response.html);
                $(RIGHT_MODAL_TITLE).html(response.title);
            }
        },
        error: function (request, status, error) {
            //console.log(request.responseText);
            if (request.status == 403) {
                $(RIGHT_MODAL_CONTENT).html(
                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">403 | Permission Denied</div>'
                );
            } else if (request.status == 404) {
                $(RIGHT_MODAL_CONTENT).html(
                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">404 | Not Found</div>'
                );
            } else if (request.status == 500) {
                $(RIGHT_MODAL_CONTENT).html(
                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">500 | Something Went Wrong</div>'
                );
            }
        },
    });
});

$("#sidebarToggle").on("click", function () {
    if ($("body").hasClass("sidebar-toggled")) {
        localStorage.setItem("mini-sidebar", "yes");
    } else {
        localStorage.setItem("mini-sidebar", "no");
    }
});

var currentUrl = window.location;
var pathArray = window.location.pathname.split("/");
if (typeof pathArray[1] !== "undefined") {
    var currentRoute = pathArray[1].split("/");
    currentRoute = currentRoute[0];
    var element = $("#sideMenuScroll li a")
        .filter(function () {
            return this.href == currentUrl.href;
        })
        .addClass("active")
        .closest("li")
        .removeClass("closeIt")
        .addClass("openIt");

    var element2 = $("#sideMenuScroll li a").filter(function () {
        var pathArray = this.href.split("/");
        if (currentRoute == pathArray[1]) {
            return true;
        }
    });
    element2.addClass("active");
    element2
        .closest("li")
        .removeClass("closeIt")
        .addClass("openIt")
        .children("a")
        .addClass("active");
}

var el = document.getElementById("close-task-detail");

$("body").on("click", ".openRightModal", openTaskDetail);

var el = document.querySelector(".closeRightModal");
if (el) {
    el.addEventListener("click", closeTaskDetail);
}

//show hide secret values
$("body").on("click", ".toggle-password", function () {
    var $selector = $(this).closest(".input-group").find("input.form-control");
    $(this).find(".svg-inline--fa").toggleClass("fa-eye fa-eye-slash");
    var $type = $selector.attr("type") === "password" ? "text" : "password";
    $selector.attr("type", $type);
});

$("body").on("click", ".openRightModal", function (event) {
    event.preventDefault();

    const requestUrl = this.href;
    const inModal = $(this).hasClass("inModal");

    let redirectUrl = "";
    if (typeof $(this).data("redirect-url") !== "undefined") {
        redirectUrl = encodeURIComponent($(this).data("redirect-url"));
    }

    $.easyAjax({
        url: requestUrl,
        blockUI: true,
        container: RIGHT_MODAL,
        historyPush: !inModal,
        data: { redirectUrl: redirectUrl },
        success: function (response) {
            if (response.status == "success") {
                $(RIGHT_MODAL_CONTENT).html(response.html);
                $(RIGHT_MODAL_TITLE).html(response.title);
            }
        },
        error: function (request, status, error) {
            //console.log(request.responseText);
            if (request.status == 403) {
                $(RIGHT_MODAL_CONTENT).html(
                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">403 | Permission Denied</div>'
                );
            } else if (request.status == 404) {
                $(RIGHT_MODAL_CONTENT).html(
                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">404 | Not Found</div>'
                );
            } else if (request.status == 500) {
                $(RIGHT_MODAL_CONTENT).html(
                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">500 | Something Went Wrong</div>'
                );
            }
        },
    });
});

// Sidebar open close
$("#sidebarToggle").on("click", function () {
    if ($("body").hasClass("sidebar-toggled")) {
        localStorage.setItem("mini-sidebar", "yes");
    } else {
        localStorage.setItem("mini-sidebar", "no");
    }
});

// active left sub menu item
var currentUrl = window.location;
var pathArray = window.location.pathname.split("account/");
if (typeof pathArray[1] !== "undefined") {
    var currentRoute = pathArray[1].split("/");
    currentRoute = currentRoute[0];
    var element = $("#sideMenuScroll li a")
        .filter(function () {
            return this.href == currentUrl.href;
        })
        .addClass("active")
        .closest("li")
        .removeClass("closeIt")
        .addClass("openIt");

    // active left main menu item
    var element2 = $("#sideMenuScroll li a").filter(function () {
        var pathArray = this.href.split("account/");
        if (currentRoute == pathArray[1]) {
            return true;
        }
        // console.log(this.href, currentUrl.href, currentUrl.href.indexOf(this.href));
    });
    element2.addClass("active");
    element2
        .closest("li")
        .removeClass("closeIt")
        .addClass("openIt")
        .children("a")
        .addClass("active");
}

//nl2br
function nl2br(str, is_xhtml) {
    if (typeof str === "undefined" || str === null) {
        return "";
    }
    var breakTag =
        is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
    return (str + "").replace(
        /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
        "$1" + breakTag + "$2"
    );
}
//decimal format
function decimalupto2(num) {
    var amt = Math.round(num * 100) / 100;
    return parseFloat(amt.toFixed(2));
}

//calculate total of invoices
function calculateTotal() {
    var subtotal = 0;
    var discount = 0;
    var tax = "";
    var taxList = new Object();
    var taxTotal = 0;
    var discountAmount = 0;
    var discountType = $("#discount_type").val();
    var discountValue = $(".discount_value").val();
    var calculateTax = $("#calculate_tax").val();
    var adjustmentAmount = $("#adjustment_amount").val();

    $(".quantity").each(function (index, element) {
        var discountedAmount = 0;
        var amount = parseFloat(
            $(this).closest(".item-row").find(".amount").val()
        );

        if (isNaN(amount)) {
            amount = 0;
        }

        subtotal = (parseFloat(subtotal) + parseFloat(amount)).toFixed(2);
    });

    if (discountType == "percent" && discountValue != "") {
        discountAmount =
            (parseFloat(subtotal) / 100) * parseFloat(discountValue);
        discountedAmount = parseFloat(subtotal - discountAmount);
    } else {
        discountAmount = parseFloat(discountValue);
        discountedAmount = parseFloat(subtotal - parseFloat(discountValue));
    }

    $(".quantity").each(function (index, element) {
        var itemTax = [];
        var itemTaxName = [];
        subtotal = parseFloat(subtotal);

        $(this)
            .closest(".item-row")
            .find("select.type option:selected")
            .each(function (index) {
                itemTax[index] = $(this).data("rate");
                itemTaxName[index] = $(this).data('tax-text');
            });
        var itemTaxId = $(this).closest(".item-row").find("select.type").val();

        var amount = parseFloat(
            $(this).closest(".item-row").find(".amount").val()
        );

        if (isNaN(amount)) {
            amount = 0;
        }

        if (itemTaxId != "") {
            for (var i = 0; i <= itemTaxName.length; i++) {
                if (typeof taxList[itemTaxName[i]] === "undefined") {
                    if (
                        calculateTax == "after_discount" &&
                        discountAmount > 0
                    ) {
                        var taxValue =
                            (amount - (amount / subtotal) * discountAmount) *
                            (parseFloat(itemTax[i]) / 100);

                        if (!isNaN(taxValue)) {
                            taxList[itemTaxName[i]] = parseFloat(taxValue);
                        }
                    } else {
                        var taxValue = amount * (parseFloat(itemTax[i]) / 100);

                        if (!isNaN(taxValue)) {
                            taxList[itemTaxName[i]] = parseFloat(taxValue);
                        }
                    }
                } else {
                    if (
                        calculateTax == "after_discount" &&
                        discountAmount > 0
                    ) {
                        var taxValue =
                            parseFloat(taxList[itemTaxName[i]]) +
                            (amount - (amount / subtotal) * discountAmount) *
                            (parseFloat(itemTax[i]) / 100);

                        if (!isNaN(taxValue)) {
                            taxList[itemTaxName[i]] = parseFloat(taxValue);
                        }
                    } else {
                        var taxValue =
                            parseFloat(taxList[itemTaxName[i]]) +
                            amount * (parseFloat(itemTax[i]) / 100);

                        if (!isNaN(taxValue)) {
                            taxList[itemTaxName[i]] = parseFloat(taxValue);
                        }
                    }
                }
            }
        }
    });

    $.each(taxList, function (key, value) {
        if (!isNaN(value)) {
            tax =
                tax +
                '<tr><td class="text-dark-grey">' +
                key +
                '</td><td><span class="tax-percent">' +
                decimalupto2(value).toFixed(2) +
                "</span></td></tr>";
            taxTotal = taxTotal + decimalupto2(value);
        }
    });

    if (isNaN(subtotal)) {
        subtotal = 0;
    }

    $(".sub-total").html(decimalupto2(subtotal).toFixed(2));
    $(".sub-total-field").val(decimalupto2(subtotal));

    if (discountValue != "") {
        if (discountType == "percent") {
            discount = (parseFloat(subtotal) / 100) * parseFloat(discountValue);
        } else {
            discount = parseFloat(discountValue);
        }
    }

    if (tax != "") {
        $("#invoice-taxes").html(tax);
    } else {
        $("#invoice-taxes").html(
            '<tr><td colspan="2"><span class="tax-percent">0.00</span></td></tr>'
        );
    }

    if (adjustmentAmount && adjustmentAmount != 0 && adjustmentAmount != '') {
        subtotal = subtotal + parseFloat(adjustmentAmount);
    }

    $("#discount_amount").html(decimalupto2(discount).toFixed(2));

    var totalAfterDiscount = decimalupto2(subtotal - discount);

    totalAfterDiscount = totalAfterDiscount < 0 ? 0 : totalAfterDiscount;

    var total = decimalupto2(totalAfterDiscount + taxTotal);

    $(".total").html(total.toFixed(2));
    $(".total-field").val(total.toFixed(2));
}

function deSelectAll() {
    $("#select-all-table").prop("checked", false);
}

$("table th:first-child").removeAttr("title");

//Prevent sidebar dropdown close
$(document).on("click", ".main-sidebar .dropdown-menu", function (e) {
    e.stopPropagation();
});

//submit form on press enter
$(document).on("keypress", "input.form-control", function(e) {
    var inModalLg = $(MODAL_LG).hasClass("show");
    var inModalXl = $(MODAL_XL).hasClass("show");

    if (e.key === "Enter") {
        if (inModalLg || inModalXl) {
            $(this)
                .closest(".modal-content")
                .find(".btn-primary")
                .trigger("click");
        } else {
            $(this).closest("form").find(".btn-primary").trigger("click");
        }
        return false; //<---- Add this line
    }
});

$("body").on("click", "#right-modal-content .btn-cancel", function (e) {
    e.preventDefault();
    closeTaskDetail();
});

//hide tooltip after click on element
$(document).on('mousedown', "[aria-describedby]", function() {
    $("[aria-describedby]").tooltip('hide');
});

// Snippet to reload the page on browser back and forward button click
$(document).ready(function () {
    sessionStorage.setItem("RIGHT_MODAL", "closed");
    if (window.history && window.history.pushState) {
        $(window).on("popstate", function () {
            if (sessionStorage.getItem("RIGHT_MODAL") != "opened") {
                window.location.reload();
            }
        });
    }
});

$('#mobile_menu_collapse').on('click', '.dropdown-item', function() {
    $("#dropdownMenuLink").dropdown("toggle");
});
