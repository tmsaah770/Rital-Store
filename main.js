var loginForm = document.getElementById("login-form");

if (loginForm) {
    loginForm.onsubmit = function () {
        var email = document.getElementById("admin-email").value;
        var password = document.getElementById("admin-password").value;

        if (email == "88902@gmail.com" && password == "88902") {
            window.location.href = "dashboard.html";
        } else if (email == "abood@gmail.com" && password == "88902") {
            sessionStorage.setItem("userRole", "orders-admin");
            window.location.href = "Employees.html";
        } else if (email == "12345@gmail.com" && password == "12345") {
            sessionStorage.setItem("userRole", "employee");
            window.location.href = "Employees.html";
        } else {
            document.getElementById("login-error").innerHTML =
                "البريد الإلكتروني أو كلمة المرور غير صحيحة";
        }
        return false;
    };
}

var itemsPerPage = 10;
var showMoreBtn = document.getElementById("btn-show-more");
var allProducts = document.querySelectorAll(".product-card");
var totalProducts = allProducts.length;
var visibleCount = itemsPerPage;

function updateProductsDisplay() {
    var i;
    for (i = 0; i < allProducts.length; i++) {
        if (i < visibleCount) {
            allProducts[i].style.display = "block";
        } else {
            allProducts[i].style.display = "none";
        }
    }

    if (totalProducts <= itemsPerPage) {
        showMoreBtn.style.display = "none";
        return;
    }

    if (visibleCount >= totalProducts) {
        showMoreBtn.textContent = "عرض أقل";
    } else {
        showMoreBtn.textContent = "عرض المزيد";
    }
}

if (showMoreBtn) {
    updateProductsDisplay();

    showMoreBtn.onclick = function () {
        if (showMoreBtn.textContent === "عرض أقل") {
            visibleCount = visibleCount - itemsPerPage;
            if (visibleCount < itemsPerPage) {
                visibleCount = itemsPerPage;
            }
        } else {
            visibleCount = visibleCount + itemsPerPage;
            if (visibleCount > totalProducts) {
                visibleCount = totalProducts;
            }
        }
        updateProductsDisplay();
    };
}

var colorDots = document.querySelectorAll(".color-dot");
var sizeBtns = document.querySelectorAll(".size-btn");
var colorText = document.getElementById("chosen-color");
var sizeText = document.getElementById("chosen-size");

if (colorText && sizeText) {
    var i;

    for (i = 0; i < colorDots.length; i++) {
        colorDots[i].onclick = function () {
            var j;
            for (j = 0; j < colorDots.length; j++) {
                colorDots[j].classList.remove("selected");
            }
            this.classList.add("selected");
            colorText.textContent = this.getAttribute("data-color");
        };
    }

    for (i = 0; i < sizeBtns.length; i++) {
        sizeBtns[i].onclick = function () {
            var j;
            for (j = 0; j < sizeBtns.length; j++) {
                sizeBtns[j].classList.remove("selected");
            }
            this.classList.add("selected");
            sizeText.textContent = this.getAttribute("data-size");
        };
    }
}

var checkoutOverlay = document.getElementById("checkout-overlay");
var checkoutOpenBtn = document.getElementById("whatsapp-order-btn");
var checkoutCloseBtn = document.getElementById("checkout-close");
var checkoutForm = document.getElementById("checkout-form");

if (checkoutOpenBtn && checkoutOverlay) {

    var productNameEl = document.querySelector(".product-detail-name");
    var priceEl = document.querySelector(".product-detail-price");
    var bigPhoto = document.getElementById("big-photo");
    var storePhoneNumber = "201000000000";

    checkoutOpenBtn.onclick = function () {
        document.getElementById("checkout-product-img").src = bigPhoto.src;
        document.getElementById("checkout-product-name").textContent = productNameEl.textContent;
        document.getElementById("checkout-product-options").textContent =
            "اللون: " + colorText.textContent + " - المقاس: " + sizeText.textContent;

        var priceNow = priceEl.textContent.trim();
        document.getElementById("checkout-product-price").textContent = priceNow;
        document.getElementById("checkout-subtotal").textContent = priceNow;
        document.getElementById("checkout-total").textContent = priceNow;

        checkoutOverlay.classList.add("active");
    };

    checkoutCloseBtn.onclick = function () {
        checkoutOverlay.classList.remove("active");
    };

    checkoutOverlay.onclick = function (e) {
        if (e.target === checkoutOverlay) {
            checkoutOverlay.classList.remove("active");
        }
    };

    checkoutForm.onsubmit = function (e) {
        e.preventDefault();

        var name = document.getElementById("checkout-name").value.trim();
        var phone = document.getElementById("checkout-phone").value.trim();
        var address = document.getElementById("checkout-address").value.trim();
        var city = document.getElementById("checkout-city").value.trim();

        if (!name || !phone || !address || !city) {
            document.getElementById("checkout-error").textContent = "من فضلك املأي كل الحقول";
            return;
        }

        var message =
            "طلب جديد (دفع عند الاستلام)\n" +
            "المنتج: " + productNameEl.textContent + "\n" +
            "اللون: " + colorText.textContent + "\n" +
            "المقاس: " + sizeText.textContent + "\n" +
            "السعر: " + priceEl.textContent.trim() + "\n" +
            "الاسم: " + name + "\n" +
            "الموبايل: " + phone + "\n" +
            "العنوان: " + address + "\n" +
            "المحافظة/المدينة: " + city;

        console.log(message);

        checkoutOverlay.classList.remove("active");

        var successOverlay = document.getElementById("success-overlay");
        var successSummary = document.getElementById("success-order-summary");
        if (successSummary) {
            successSummary.textContent = "المنتج: " + productNameEl.textContent + " - الإجمالي: " + priceEl.textContent.trim();
        }
        if (successOverlay) {
            successOverlay.classList.add("active");
        }

        checkoutForm.reset();
    };

    var successCloseBtn = document.getElementById("success-close-btn");
    if (successCloseBtn) {
        successCloseBtn.onclick = function () {
            document.getElementById("success-overlay").classList.remove("active");
        };
    }
}

var dashColorRow = document.getElementById("dash-color-row");
var dashSizeRow = document.getElementById("dash-size-row");

function collectSelected(row, hiddenId, attr) {
    var values = [];
    var selected = row.querySelectorAll(".selected");
    var i;
    for (i = 0; i < selected.length; i++) {
        values.push(selected[i].getAttribute(attr));
    }
    document.getElementById(hiddenId).value = values.join(",");
}

if (dashColorRow && dashSizeRow) {
    var dashColorDots = dashColorRow.querySelectorAll(".color-dot");
    var dashSizeBtns = dashSizeRow.querySelectorAll(".size-btn");
    var i;

    for (i = 0; i < dashColorDots.length; i++) {
        dashColorDots[i].onclick = function () {
            this.classList.toggle("selected");
            collectSelected(dashColorRow, "available-colors", "data-color");
        };
    }

    for (i = 0; i < dashSizeBtns.length; i++) {
        dashSizeBtns[i].onclick = function () {
            this.classList.toggle("selected");
            collectSelected(dashSizeRow, "available-sizes", "data-size");
        };
    }
}

var stockTableWrap = document.getElementById("stock-table-wrap");
var stockValues = {};

function getActiveColors() {
    var values = [];
    var selected = dashColorRow.querySelectorAll(".selected");
    var i;
    for (i = 0; i < selected.length; i++) {
        values.push(selected[i].getAttribute("data-color"));
    }
    return values;
}

function getActiveSizes() {
    var values = [];
    var selected = dashSizeRow.querySelectorAll(".selected");
    var i;
    for (i = 0; i < selected.length; i++) {
        values.push(selected[i].getAttribute("data-size"));
    }
    return values;
}

function buildStockTable() {
    var colors = getActiveColors();
    var sizes = getActiveSizes();

    if (colors.length === 0 || sizes.length === 0) {
        stockTableWrap.innerHTML = '<p class="dash-hint">اختاري لون ومقاس واحد على الأقل عشان يظهر جدول المخزون.</p>';
        return;
    }

    var html = '<table class="stock-table"><thead><tr><th>اللون \\ المقاس</th>';
    var i, j;

    for (i = 0; i < sizes.length; i++) {
        html += "<th>" + sizes[i] + "</th>";
    }
    html += "</tr></thead><tbody>";

    for (i = 0; i < colors.length; i++) {
        html += "<tr><td>" + colors[i] + "</td>";
        for (j = 0; j < sizes.length; j++) {
            var key = colors[i] + "|" + sizes[j];
            var savedValue = stockValues[key];
            if (savedValue === undefined) {
                savedValue = 0;
            }
            html += '<td><input type="number" min="0" value="' + savedValue + '" data-color="' + colors[i] + '" data-size="' + sizes[j] + '"></td>';
        }
        html += "</tr>";
    }

    html += "</tbody></table>";
    stockTableWrap.innerHTML = html;

    var stockInputs = stockTableWrap.querySelectorAll("input");
    for (i = 0; i < stockInputs.length; i++) {
        stockInputs[i].onchange = function () {
            var key = this.getAttribute("data-color") + "|" + this.getAttribute("data-size");
            stockValues[key] = this.value;
        };
    }
}

if (stockTableWrap && dashColorRow && dashSizeRow) {
    buildStockTable();

    var stockColorDots = dashColorRow.querySelectorAll(".color-dot");
    var stockSizeBtns = dashSizeRow.querySelectorAll(".size-btn");
    var i;

    for (i = 0; i < stockColorDots.length; i++) {
        stockColorDots[i].addEventListener("click", buildStockTable);
    }
    for (i = 0; i < stockSizeBtns.length; i++) {
        stockSizeBtns[i].addEventListener("click", buildStockTable);
    }
}

var branchFilter = document.getElementById("branch-filter");

if (branchFilter) {
    branchFilter.onchange = function () {
        var selectedBranch = branchFilter.value;
        var orderCards = document.querySelectorAll(".order-card");
        var i;
        for (i = 0; i < orderCards.length; i++) {
            if (selectedBranch === "all" || orderCards[i].getAttribute("data-branch") === selectedBranch) {
                orderCards[i].style.display = "block";
            } else {
                orderCards[i].style.display = "none";
            }
        }
    };
}

var employeeOrdersSection = document.getElementById("employee-orders-section");
var employeeBranchLabel = document.getElementById("employee-branch-label");
var employeeLogoutBtn = document.getElementById("employee-logout-btn");
var adminDashboardLink = document.getElementById("admin-dashboard-link");

if (employeeOrdersSection) {
    var userRole = sessionStorage.getItem("userRole");

    if (userRole === "orders-admin") {

        employeeOrdersSection.style.display = "block";

        if (employeeBranchLabel) {
            employeeBranchLabel.textContent = "- كل الفروع (أدمن)";
        }
        if (adminDashboardLink) {
            adminDashboardLink.style.display = "inline-block";
        }

        var editBtns = document.querySelectorAll(".admin-edit-btn");
        var deleteBtns = document.querySelectorAll(".admin-delete-btn");
        var i;
        for (i = 0; i < editBtns.length; i++) {
            editBtns[i].style.display = "inline-block";
        }
        for (i = 0; i < deleteBtns.length; i++) {
            deleteBtns[i].style.display = "inline-block";
        }

    } else if (userRole === "employee") {

        employeeOrdersSection.style.display = "block";

        if (employeeBranchLabel) {
            employeeBranchLabel.textContent = "";
        }

    } else {
        window.location.href = "login.html";
    }
}

if (employeeLogoutBtn) {
    employeeLogoutBtn.onclick = function () {
        sessionStorage.removeItem("userRole");
        window.location.href = "login.html";
    };
}

var colorOptions = ["أسود", "رمادي", "بيج", "كحلي", "بني", "ابيض"];
var sizeOptions = ["S", "M", "L", "XL", "2XL"];
var branchOptions = ["1", "2", "3", "4", "5"];

function buildFieldInput(fieldName, currentValue) {
    var i, html;

    if (fieldName === "color") {
        html = '<select class="field-input" data-field="color">';
        for (i = 0; i < colorOptions.length; i++) {
            var sel = colorOptions[i] === currentValue ? " selected" : "";
            html += '<option value="' + colorOptions[i] + '"' + sel + '>' + colorOptions[i] + '</option>';
        }
        html += '</select>';
        return html;
    }

    if (fieldName === "size") {
        html = '<select class="field-input" data-field="size">';
        for (i = 0; i < sizeOptions.length; i++) {
            var sel2 = sizeOptions[i] === currentValue ? " selected" : "";
            html += '<option value="' + sizeOptions[i] + '"' + sel2 + '>' + sizeOptions[i] + '</option>';
        }
        html += '</select>';
        return html;
    }

    if (fieldName === "branch") {
        var branchNumber = currentValue.replace("فرع", "").trim();
        html = '<select class="field-input" data-field="branch">';
        for (i = 0; i < branchOptions.length; i++) {
            var sel3 = branchOptions[i] === branchNumber ? " selected" : "";
            html += '<option value="' + branchOptions[i] + '"' + sel3 + '>فرع ' + branchOptions[i] + '</option>';
        }
        html += '</select>';
        return html;
    }

    if (fieldName === "price") {
        return '<input type="number" min="0" class="field-input" data-field="price" value="' + currentValue + '">';
    }

    if (fieldName === "phone") {
        return '<input type="tel" class="field-input" data-field="phone" value="' + currentValue + '">';
    }

    if (fieldName === "email") {
        var emailValue = currentValue === "لا يوجد" ? "" : currentValue;
        return '<input type="email" class="field-input" data-field="email" value="' + emailValue + '" placeholder="لا يوجد">';
    }

    return '<input type="text" class="field-input" data-field="' + fieldName + '" value="' + currentValue + '">';
}

function setupEditButton(editBtn) {
    editBtn.onclick = function () {
        var orderCard = this.closest(".order-card");
        var isEditing = this.getAttribute("data-editing") === "true";

        if (!isEditing) {
            var fields = orderCard.querySelectorAll("[data-field]");
            var i;
            for (i = 0; i < fields.length; i++) {
                var fieldEl = fields[i];
                var fieldName = fieldEl.getAttribute("data-field");
                var currentValue = fieldEl.textContent.trim();
                fieldEl.setAttribute("data-old-html", "span");
                fieldEl.setAttribute("data-current-value", currentValue);
                fieldEl.innerHTML = buildFieldInput(fieldName, currentValue);
            }

            this.textContent = "حفظ";
            this.setAttribute("data-editing", "true");

        } else {
            var fields2 = orderCard.querySelectorAll("[data-field]");
            var j;
            for (j = 0; j < fields2.length; j++) {
                var fieldEl2 = fields2[j];
                var input = fieldEl2.querySelector(".field-input");
                var newValue;

                if (input) {
                    if (input.tagName === "SELECT" && input.getAttribute("data-field") === "branch") {
                        newValue = "فرع " + input.value;
                    } else if (input.getAttribute("data-field") === "email" && input.value.trim() === "") {
                        newValue = "لا يوجد";
                    } else {
                        newValue = input.value;
                    }
                } else {
                    newValue = fieldEl2.getAttribute("data-current-value");
                }

                fieldEl2.textContent = newValue;
            }

            var branchSpan = orderCard.querySelector('[data-field="branch"]');
            if (branchSpan) {
                var branchNum = branchSpan.textContent.replace("فرع", "").trim();
                orderCard.setAttribute("data-branch", branchNum);
            }

            this.textContent = "تعديل";
            this.setAttribute("data-editing", "false");

            var successMsg = orderCard.querySelector(".save-success-msg");
            successMsg.classList.add("show");
            setTimeout(function () {
                successMsg.classList.remove("show");
            }, 2500);

        }
    };
}

function setupDeleteButton(deleteBtn) {
    deleteBtn.onclick = function () {
        var orderCard = this.closest(".order-card");
        var orderId = orderCard.querySelector(".order-id").textContent;

        if (confirm("متأكدة إنك عايزة تحذفي الأوردر " + orderId + "؟")) {
            orderCard.remove();
        }
    };
}

var existingEditBtns = document.querySelectorAll(".admin-edit-btn");
var existingDeleteBtns = document.querySelectorAll(".admin-delete-btn");
var k;
for (k = 0; k < existingEditBtns.length; k++) {
    setupEditButton(existingEditBtns[k]);
}
for (k = 0; k < existingDeleteBtns.length; k++) {
    setupDeleteButton(existingDeleteBtns[k]);
}

var createOrderBtn = document.getElementById("create-order-btn");
var ordersListEl = document.getElementById("orders-list");

function getNextOrderId() {
    var ids = document.querySelectorAll(".order-id");
    var maxId = 1000;
    var i;
    for (i = 0; i < ids.length; i++) {
        var num = parseInt(ids[i].textContent.replace("#", ""), 10);
        if (num > maxId) {
            maxId = num;
        }
    }
    return maxId + 1;
}

var failureOverlay = document.getElementById("failure-overlay");
var failureCloseBtn = document.getElementById("failure-close");
var failureConfirmBtn = document.getElementById("failure-confirm-btn");
var currentFailureSelect = null;

function setupOrderStatusSelect(select) {
    select.setAttribute("data-value", select.value);

    select.onchange = function () {
        var orderId = this.getAttribute("data-order-id");
        var orderCard = this.closest(".order-card");
        var failureNote = orderCard.querySelector(".failure-note");

        this.setAttribute("data-value", this.value);

        if (this.value === "failed") {
            currentFailureSelect = this;
            document.getElementById("failure-order-id").textContent = orderId;
            failureOverlay.classList.add("active");
        } else {
            failureNote.style.display = "none";
        }

        console.log("تحديث الأوردر " + orderId + " إلى الحالة: " + this.value);
    };
}

var orderStatusSelects = document.querySelectorAll(".order-status");
var s;
for (s = 0; s < orderStatusSelects.length; s++) {
    setupOrderStatusSelect(orderStatusSelects[s]);
}

if (failureCloseBtn) {
    failureCloseBtn.onclick = function () {
        if (currentFailureSelect) {
            currentFailureSelect.value = currentFailureSelect.getAttribute("data-value");
        }
        failureOverlay.classList.remove("active");
    };
}

if (failureConfirmBtn) {
    failureConfirmBtn.onclick = function () {
        var reasonSelect = document.getElementById("failure-reason-select");
        var reasonDetails = document.getElementById("failure-reason-details").value.trim();

        if (!reasonSelect.value) {
            document.getElementById("failure-error").textContent = "من فضلك اختاري سبب فشل التوصيل";
            return;
        }

        var orderCard = currentFailureSelect.closest(".order-card");
        var failureNote = orderCard.querySelector(".failure-note");
        var failureText = orderCard.querySelector(".failure-reason-text");
        var reasonLabel = reasonSelect.options[reasonSelect.selectedIndex].text;

        failureText.textContent = reasonLabel + (reasonDetails ? " - " + reasonDetails : "");
        failureNote.style.display = "block";
        currentFailureSelect.setAttribute("data-value", "failed");

        console.log("سبب فشل التوصيل للأوردر:", reasonLabel, reasonDetails);

        failureOverlay.classList.remove("active");
        reasonSelect.value = "";
        document.getElementById("failure-reason-details").value = "";
        document.getElementById("failure-error").textContent = "";
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const createBtn = document.getElementById('create-order-btn');
    const overlay = document.getElementById('new-order-overlay');
    const closeBtn = document.getElementById('new-order-close');
    const confirmBtn = document.getElementById('new-order-confirm-btn');
    const ordersList = document.getElementById('orders-list');
    const errorEl = document.getElementById('new-order-error');

    if (!createBtn) return;

    createBtn.addEventListener('click', () => overlay.classList.add('active'));
    closeBtn.addEventListener('click', () => overlay.classList.remove('active'));

    confirmBtn.addEventListener('click', () => {
        const branch = document.getElementById('new-order-branch').value.trim();
        const type = document.getElementById('new-order-type').value.trim();
        const color = document.getElementById('new-order-color').value.trim();
        const size = document.getElementById('new-order-size').value.trim();
        const price = document.getElementById('new-order-price').value.trim();
        const customer = document.getElementById('new-order-customer').value.trim();
        const phone = document.getElementById('new-order-phone').value.trim();
        const email = document.getElementById('new-order-email').value.trim() || 'لا يوجد';
        const address = document.getElementById('new-order-address').value.trim();

        if (!type || !price || !customer || !phone || !address) {
            errorEl.textContent = 'من فضلك املأ الحقول الأساسية (النوع، السعر، العميل، الهاتف، العنوان)';
            return;
        }
        errorEl.textContent = '';

        const now = new Date();
        const timeStr = now.toLocaleString('ar-EG', { hour: '2-digit', minute: '2-digit' });
        const newId = Math.floor(1000 + Math.random() * 9000);

        const card = document.createElement('div');
        card.className = 'order-card';
        card.setAttribute('data-branch', branch);
        card.innerHTML = `
            <div class="order-header">
                <span class="order-id">#${newId}</span>
                <span class="order-branch" data-field="branch">${branch || 'غير محدد'}</span>
                <span class="order-date">تم الطلب: <span data-field="createdDate">الآن - ${timeStr}</span></span>
            </div>
            <div class="order-body">
                <p class="order-type"><strong>نوع الأوردر:</strong> <span data-field="type">${type}</span></p>
                <p><strong>اللون:</strong> <span data-field="color">${color || '-'}</span> &nbsp; | &nbsp; <strong>المقاس:</strong> <span data-field="size">${size || '-'}</span></p>
                <p class="order-price"><strong>السعر:</strong> <span data-field="price">${price}</span> جنيه (دفع عند الاستلام)</p>
                <hr class="order-divider">
                <p><strong>العميل:</strong> <span data-field="customer">${customer}</span></p>
                <p><strong>الهاتف:</strong> <span data-field="phone">${phone}</span></p>
                <p><strong>الإيميل:</strong> <span data-field="email">${email}</span></p>
                <p><strong>العنوان:</strong> <span data-field="address">${address}</span></p>
                <hr class="order-divider">
                <p class="order-timing"><strong>وقت الإنشاء:</strong> <span data-field="createdTime">${timeStr}</span></p>
                <p class="order-timing"><strong>وقت التسليم:</strong> <span data-field="deliveredTime">لسه ماتسلمش</span></p>
            </div>
            <div class="order-footer">
                <select class="order-status" data-order-id="${newId}">
                    <option value="preparing">قيد التجهيز</option>
                    <option value="out_for_delivery">قيد التوصيل</option>
                    <option value="delivered">تم التوصيل</option>
                    <option value="failed">فشل التوصيل</option>
                </select>
            </div>
            <div class="order-actions">
                <span class="save-success-msg">تم الحفظ بنجاح</span>
                <button type="button" class="admin-edit-btn">تعديل</button>
                <button type="button" class="admin-delete-btn">حذف</button>
            </div>
        `;

        ordersList.insertBefore(card, ordersList.firstChild);
        overlay.classList.remove('active');

        document.querySelectorAll('#new-order-overlay .input').forEach(i => i.value = '');

        const newEditBtn = card.querySelector('.admin-edit-btn');
        const newDeleteBtn = card.querySelector('.admin-delete-btn');
        const newStatusSelect = card.querySelector('.order-status');
        setupEditButton(newEditBtn);
        setupDeleteButton(newDeleteBtn);
        setupOrderStatusSelect(newStatusSelect);
    });
});