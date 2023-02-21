renderCompareTable();
renderCompareCount();
setAllCompareButtonsState();

function afterFilterComplete() {
    setAllCompareButtonsState();
}

function addToCompareList() {
    // Get the current comparison list from the cookie
    const compareList = getCompareList();
    const productId = this.dataset.id;

    // Check if the product is already in the comparison list
    if (!compareList.includes(productId)) {
        // Check if the comparison list has reached its limit
        if (compareList.length >= compareMaxCount) {
            showCompareListLimitWarning();
            return;
        }

        // Add the product to the comparison list
        compareList.push(productId);
        updateCompareListCookie(compareList);
    }
    // Update the button text and class
    updateCompareButtonState(this);
    // Update comparison table and count
    renderCompareTable();
    renderCompareCount();

}

function removeFromCompareList() {
    // Get the current comparison list from the cookie
    const compareList = getCompareList();
    const productId = this.dataset.id;

    // Check if the product is in the comparison list
    if (compareList.includes(productId)) {
        // Remove the product from the comparison list
        const index = compareList.indexOf(productId);
        compareList.splice(index, 1);
        updateCompareListCookie(compareList);
    }
    // Update the button text and class
    updateCompareButtonState(this);
    // Update comparison table and count
    renderCompareTable();
    renderCompareCount();
}

function getCompareList() {
    // Check if the cookie exists
    if (document.cookie.indexOf("compare_list") === -1) {
        return [];
    }

    // Extract the comparison list from the cookie
    const compareListCookie = document.cookie.split(";").find(cookie => cookie.trim().startsWith("compare_list="));
    const compareList = compareListCookie.split("=")[1].split(",");

    return compareList;
}

function showCompareListLimitWarning() {
    alert(compareMaxCountMsg);
}

function updateCompareListCookie(compareList) {
    if (compareList.length > 0) {
        // Convert the comparison list array into a string
        const compareListString = compareList.join(",");

        // Set the cookie with the updated comparison list
        document.cookie = `compare_list=${compareListString};max-age=31536000;path=/`;
    } else {
        // Delete cookie
        document.cookie = "compare_list=;max-age=-1;path=/";
    }
}

function updateCompareButtonState(el) {
    // Get the current comparison list from the cookie
    const compareList = getCompareList();
    const productId = el.dataset.id;

    // Check if the product is in the comparison list
    if (compareList.includes(productId)) {
        el.removeEventListener("click", addToCompareList);
        el.addEventListener("click", removeFromCompareList);
        el.dataset.action = "removeFromCompareList";
        el.classList.add(compareActiveClass);
    } else {
        el.removeEventListener("click", removeFromCompareList);
        el.addEventListener("click", addToCompareList);
        el.dataset.action = "addToCompareList";
        el.classList.remove(compareActiveClass);
    }
}

function setAllCompareButtonsState() {
    // Find all compare buttons on page
    const compareButtons = document.querySelectorAll("[data-role='compareButton']");
    if (compareButtons.length === 0) {
        return;
    }

    // Get the current comparison list from the cookie
    const compareList = getCompareList();

    compareButtons.forEach(compareButton => {
        // Check if the product is in the comparison list
        if (compareList.includes(compareButton.dataset.id)) {
            compareButton.dataset.action = "removeFromCompareList";
            compareButton.classList.add(compareActiveClass);
            compareButton.removeEventListener("click", addToCompareList);
            compareButton.addEventListener("click", removeFromCompareList);
        } else {
            compareButton.dataset.action = "addToCompareList";
            compareButton.classList.remove(compareActiveClass);
            compareButton.removeEventListener("click", removeFromCompareList);
            compareButton.addEventListener("click", addToCompareList);
        }
    });

    // Find compare Clear button
    const compareClearButton = document.querySelector('[data-role="compareClearButton"]');
    if (compareClearButton) {
        // To be on the safe side, we first remove the listener
        compareClearButton.removeEventListener("click", clearCompareData);
        compareClearButton.addEventListener("click", clearCompareData);
    }
}

function renderCompareTable() {
    const tableWrapper = document.querySelector('[data-role="compareTable"]');
    if (!tableWrapper) {
        return;
    }

    const compareList = getCompareList();
    if (compareList.length >= 0) {///@HACK
        fetch("/compare-list-render", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ products: compareList })
        })
            .then(response => response.text())
            .then(html => {
                tableWrapper.innerHTML = html;
                setAllCompareButtonsState();
            })
    } else {
        tableWrapper.innerHTML = '';
    }
}

function clearCompareData() {
    // Clear comparison "compare_list" cookies and clear HTML in comparison table
    const compareList = [];
    updateCompareListCookie(compareList);

    renderCompareCount();
    renderCompareTable();
    setAllCompareButtonsState();
}

function renderCompareCount() {
    // Get the current comparison list from the cookie
    const compareList = getCompareList();
    const count = compareList.length;

    const compareCountWrap = document.querySelector('[data-role="compareCountWrap"]');
    const compareCount = document.querySelector('[data-role="compareCount"]');

    if (!compareCountWrap) {
        return;
    }

    if (count > 0) {
        compareCount.innerHTML = count;
        compareCountWrap.classList.add(compareActiveClass);
        compareCount.classList.add(compareActiveClass);
    } else {
        compareCount.innerHTML = '';
        compareCountWrap.classList.remove(compareActiveClass);
        compareCount.classList.remove(compareActiveClass);
    }
}
