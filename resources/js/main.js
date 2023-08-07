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
