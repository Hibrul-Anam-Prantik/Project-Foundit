// --- MOCK DATABASE (LOCAL STORAGE) ---
let allItems = [
    {
        id: 1,
        type: "lost", // 'lost' or 'found'
        title: "Lost Black Wallet",
        category: "accessories",
        location: "UB2 Cafeteria",
        date: "2025-11-07T14:30:00Z", // ISO 8601 format
        description: "Standard black leather wallet. Contains student ID and some cash.",
        hiddenDetail: "Has a small 'P' keychain inside",
        imageUrl: "https://placehold.co/600x400/555/FFF?text=Lost+Wallet",
        status: "unclaimed" // 'unclaimed', 'verification', 'returned'
    },
    {
        id: 2,
        type: "found",
        title: "Found iPhone 13",
        category: "electronics",
        location: "Library, 5th Floor",
        date: "2025-11-08T09:15:00Z",
        description: "Found a blue iPhone 13 on a desk. Lock screen is a cat.",
        hiddenDetail: "The lock screen is a white cat with blue eyes.",
        imageUrl: "https://placehold.co/600x400/3498db/FFF?text=Found+iPhone",
        status: "unclaimed"
    },
    {
        id: 3,
        type: "lost",
        title: "Lost CSE110 Textbook",
        category: "books",
        location: "UB5 8th floor, near lift",
        date: "2025-11-06T11:00:00Z",
        description: "My 'Structured Programming Language' book. Has a red cover.",
        hiddenDetail: "My name 'Asif' is written on the first page.",
        imageUrl: "https://placehold.co/600x400/e74c3c/FFF?text=Lost+Book",
        status: "returned"
    },
    {
        id: 4,
        type: "found",
        title: "Found BRACU ID Card",
        category: "id-cards",
        location: "Ghaashful",
        date: "2025-11-08T13:00:00Z", // Recent item
        description: "Found an ID card. Name is partially visible.",
        hiddenDetail: "The ID number is 2210XXXXX",
        imageUrl: "https://placehold.co/600x400/2ecc71/FFF?text=Found+ID",
        status: "unclaimed"
    }
];

// --- GLOBAL DOM REFERENCES ---
let itemFeedContainer, quickFeedContainer, postModal, claimModal, customAlertModal;
let alertCallback = null; // Global variable to store the function to run after alert closes
let currentUserEmail = null; // email of the logged-in user (simulated)
let isFounder = false; // toggle admin/founder privileges when logged in as founder

// --- CUSTOM ALERT FUNCTION ---
function closeAlertAndExecuteCallback() {
    if (customAlertModal) {
        customAlertModal.style.display = "none";
    }
    if (alertCallback) {
        alertCallback();
        alertCallback = null; // Clear the callback after execution
    }
}

function showCustomAlert(title, message, callback = null) {
    if (!customAlertModal) {
         console.error("Custom alert modal is not defined.");
         return;
    }

    // Set the callback function
    alertCallback = callback;

    document.getElementById("custom-alert-title").textContent = title;
    document.getElementById("custom-alert-message").textContent = message;
    customAlertModal.style.display = "block";
}

// --- MAIN APP INITIALIZATION (called after simulated successful login) ---
function initializeApp() {
    // These elements only exist on the main page, so they are initialized here
    itemFeedContainer = document.getElementById("item-feed-container");
    quickFeedContainer = document.getElementById("quick-feed-container");

    loadItemsFromStorage();

    renderItems(allItems, itemFeedContainer);
    renderQuickFeed(allItems, quickFeedContainer);

    setupFilterListeners();
    setupMainAppModalListeners(); 
}

function loadItemsFromStorage() {
    const storedItems = localStorage.getItem('foundit_items');
    // If there's stored data, overwrite the mock data
    if (storedItems) {
        // Parse the stored string back into a JavaScript array/object structure
        allItems = JSON.parse(storedItems);
    } else {
        // If nothing is stored, initialize storage with mock data
        saveItemsToStorage();
    }
}

function saveItemsToStorage() {
    // Convert the JavaScript array/object into a JSON string for storage
    localStorage.setItem('foundit_items', JSON.stringify(allItems));
}

// --- PAGE SWITCHING & INITIAL SETUP LOGIC ---
document.addEventListener("DOMContentLoaded", () => {

    const loginPage = document.getElementById('login-page');
    const mainAppPage = document.getElementById('main-app-page');

    // --- Modal and Close Button Setup (CRITICAL: Assign elements here) ---
    customAlertModal = document.getElementById("custom-alert-modal");
    postModal = document.getElementById("post-item-modal");
    claimModal = document.getElementById("claim-item-modal");

    // 1. Attach callback function to the OK button
    const alertOkBtn = document.getElementById("custom-alert-ok");
    alertOkBtn.addEventListener("click", closeAlertAndExecuteCallback);

    // 2. Attach callback function to the X button
    const alertCloseX = document.getElementById("alert-close-x");
    alertCloseX.addEventListener("click", closeAlertAndExecuteCallback);

    // Universal close button functionality for other modals
    const closeButtons = document.querySelectorAll(".close-btn:not(#alert-close-x)");
    closeButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            btn.closest('.modal').style.display = "none";
        });
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", (event) => {
        if (event.target == postModal || event.target == claimModal || event.target == customAlertModal) {
            // Check if it's the custom alert, and use the callback logic
            if (event.target == customAlertModal) {
                closeAlertAndExecuteCallback();
            } else {
                event.target.style.display = "none";
            }
        }
    });

    // --- Login Elements ---
    const emailLoginForm = document.getElementById('email-login-form');
    const gsuiteLoginBtn = document.getElementById('gsuite-login-btn');
    const logoutBtn = document.getElementById('logout-btn');


    // --- 1. Email/Password Login Submission (Simulated) ---
    emailLoginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('email-input').value.trim().toLowerCase();
        const password = document.getElementById('password-input').value;

        // Simple validation for simulated login
        if (email.length > 0 && password.length > 0) {
            // store the current user email and detect founder/admin
            currentUserEmail = email;
            // Change this value to the actual founder/admin email you want
            isFounder = (email === 'founder@foundit.com');

            // Immediately switch to main page and initialize the app
            loginPage.style.display = 'none';
            mainAppPage.style.display = 'block';
            initializeApp();

            // Update visible user info in the UI
            updateUserInfo();

            // Show a non-blocking success alert (no callback required)
            showCustomAlert("Login Successful", `Welcome ${currentUserEmail}${isFounder ? ' (Founder)' : ''}.`);
        } else {
            showCustomAlert("Login Failed", "Please ensure both Email and Password fields are filled.");
        }
    });

    // --- 2. G-Suite Login (Main Method) ---
    gsuiteLoginBtn.addEventListener('click', () => {
        // For demo purposes, use a placeholder G-Suite email and no founder privileges
        currentUserEmail = 'gsuite.user@foundit.com';
        isFounder = false;

        // Immediately switch to main page and initialize the app
        loginPage.style.display = 'none';
        mainAppPage.style.display = 'block';
        initializeApp();

    // Update visible user info in the UI
    updateUserInfo();

        // Show a non-blocking notice
        showCustomAlert("G-Suite Sign-in", `Signed in as ${currentUserEmail}.`);
    });


    // --- Logout ---
    if(logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            mainAppPage.style.display = 'none';
            loginPage.style.display = 'flex'; // Use flex to re-center
            showCustomAlert("Logged Out", "You have successfully logged out.");
            // clear user info
            currentUserEmail = null;
            isFounder = false;
            updateUserInfo();
        });
    }
});

// Update the small user info widget in the navbar
function updateUserInfo() {
    const el = document.getElementById('user-info');
    if (!el) return;
    if (currentUserEmail) {
        el.innerHTML = `<span class="email">${currentUserEmail}</span><span class="role">${isFounder ? 'Founder / Admin' : 'User'}</span>`;
    } else {
        el.innerHTML = '';
    }
}


// --- RENDERING FUNCTIONS (No changes needed here) ---

function renderItems(items, container) {
    if (!container) return; 

    container.innerHTML = ""; 

    // Sort by date descending (most recent first)
    const sortedItems = items.sort((a, b) => new Date(b.date) - new Date(a.date));

    if (sortedItems.length === 0) {
        container.innerHTML = "<p>No items match your criteria.</p>";
        return;
    }

    sortedItems.forEach(item => {
        const itemCard = document.createElement("div");
        itemCard.className = `item-card ${item.type}-card`;
        itemCard.setAttribute("data-id", item.id);

        const itemDate = new Date(item.date);
        const formattedDate = itemDate.toLocaleString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric', 
            hour: 'numeric', 
            minute: '2-digit' 
        });

        let buttonText = "View & Claim";
        let buttonDisabled = "";
        if (item.status === 'verification') {
            buttonText = "Under Verification";
            buttonDisabled = "disabled";
        } else if (item.status === 'returned') {
            buttonText = "Item Returned";
            buttonDisabled = "disabled";
        }

        itemCard.innerHTML = `
            <img src="${item.imageUrl || 'https://placehold.co/600x400/EEE/AAA?text=No+Image'}" alt="${item.title}" onerror="this.src='https://placehold.co/600x400/EEE/AAA?text=No+Image';">
            <div class="item-info">
                <span class="item-status status-${item.status}">${item.status.replace('-', ' ')}</span>
                <span class="item-type type-${item.type}">${item.type}</span>
                <h4 class="item-title">${item.title}</h4>
                <p class="item-location"><i class="fas fa-map-marker-alt"></i> ${item.location}</p>
                <p class="item-date"><i class="fas fa-calendar-alt"></i> ${formattedDate}</p>
                <button class="claim-btn" data-id="${item.id}" ${buttonDisabled}>${buttonText}</button>
            </div>
        `;
        container.appendChild(itemCard);
        // If item is under verification and the current user is the founder/admin, add a Verify & Return button
        if (item.status === 'verification' && isFounder) {
            const verifyBtn = document.createElement('button');
            verifyBtn.className = 'verify-return-btn';
            verifyBtn.dataset.id = item.id;
            verifyBtn.textContent = 'Verify & Return';
            verifyBtn.style.marginTop = '0.75rem';
            // Append to the item-info area
            const info = itemCard.querySelector('.item-info');
            if (info) info.appendChild(verifyBtn);
        }
    });

    addClaimButtonListeners();
}

function renderQuickFeed(items, container) {
    if (!container) return; 

    const now = new Date();
    const twentyFourHoursAgo = new Date(now.getTime() - (24 * 60 * 60 * 1000));

    const recentItems = items.filter(item => new Date(item.date) > twentyFourHoursAgo);

    if (recentItems.length > 0) {
        container.innerHTML = '';
        recentItems.forEach(item => {
            const itemCard = document.createElement('div');
            itemCard.className = `item-card ${item.type}-card`;
            itemCard.setAttribute("data-id", item.id);
            // Minimal card style for the quick horizontal feed
            itemCard.innerHTML = `
                <img src="${item.imageUrl || 'https://placehold.co/600x400/EEE/AAA?text=No+Image'}" alt="${item.title}" onerror="this.src='https://placehold.co/600x400/EEE/AAA?text=No+Image';">
                <div class="item-info">
                    <span class="item-status status-${item.status}">${item.status.replace('-', ' ')}</span>
                    <span class="item-type type-${item.type}">${item.type}</span>
                    <h4 class="item-title">${item.title}</h4>
                    <p class="item-location"><i class="fas fa-map-marker-alt"></i> ${item.location}</p>
                </div>
            `;
            container.appendChild(itemCard);
        });
        
    } else {
        container.innerHTML = "<p>No items reported in the last 24 hours.</p>";
    }
}

// --- FILTERING LOGIC (No changes needed here) ---

function setupFilterListeners() {
    const searchBtn = document.getElementById("search-btn");
    const searchBar = document.getElementById("search-bar");
    
    searchBtn.addEventListener("click", applyFilters);
    
    searchBar.addEventListener("keyup", (event) => {
        if (event.key === "Enter") {
            applyFilters();
        }
    });
    
    document.getElementById("filter-category").addEventListener("change", applyFilters);
    document.getElementById("filter-type").addEventListener("change", applyFilters);
}

function applyFilters() {
    const searchTerm = document.getElementById("search-bar").value.toLowerCase();
    const category = document.getElementById("filter-category").value;
    const type = document.getElementById("filter-type").value;

    const filteredItems = allItems.filter(item => {
        const matchesSearch = item.title.toLowerCase().includes(searchTerm) || 
                            item.description.toLowerCase().includes(searchTerm) ||
                            item.location.toLowerCase().includes(searchTerm);
        
        const matchesCategory = (category === "all") || (item.category === category);
        
        const matchesType = (type === "all") || (item.type === type);

        return matchesSearch && matchesCategory && matchesType;
    });

    renderItems(filteredItems, itemFeedContainer);
}


// --- MAIN APP MODAL & FORM LOGIC (No changes needed here) ---

function setupMainAppModalListeners() {
    const postLostBtn = document.querySelector(".post-lost");
    const postFoundBtn = document.querySelector(".post-found");
    
    const postForm = document.getElementById("post-item-form");
    const claimForm = document.getElementById("claim-item-form");

    postLostBtn.addEventListener("click", (e) => {
        e.preventDefault();
        openPostModal('lost');
    });

    postFoundBtn.addEventListener("click", (e) => {
        e.preventDefault();
        openPostModal('found');
    });

    postForm.addEventListener("submit", handlePostSubmit);
    claimForm.addEventListener("submit", handleClaimSubmit);
}

function openPostModal(type) {
    const title = document.getElementById("modal-title");
    const formType = document.getElementById("form-type");
    
    // Reset the form before opening
    document.getElementById("post-item-form").reset();
    
    if (type === 'lost') {
        title.textContent = "Report a Lost Item";
        formType.value = "lost";
    } else {
        title.textContent = "Register a Found Item";
        formType.value = "found";
    }
    
    postModal.style.display = "block";
}

function addClaimButtonListeners() {
    // Remove existing listeners and re-add them to prevent duplicates on re-render
    document.querySelectorAll(".claim-btn").forEach(oldBtn => {
        const newBtn = oldBtn.cloneNode(true);
        oldBtn.parentNode.replaceChild(newBtn, oldBtn);

        if (!newBtn.disabled) {
            newBtn.addEventListener("click", openClaimModal);
        }
    });

    // Admin verify/return buttons
    document.querySelectorAll('.verify-return-btn').forEach(oldBtn => {
        const newBtn = oldBtn.cloneNode(true);
        oldBtn.parentNode.replaceChild(newBtn, oldBtn);
        newBtn.addEventListener('click', verifyAndReturn);
    });
}

function openClaimModal(event) {
    const itemId = event.target.dataset.id;
    
    const item = allItems.find(i => i.id == itemId);
    if (!item) return;
    
    document.getElementById("claim-item-name").textContent = item.title;
    document.getElementById("claim-item-id").value = item.id;
    
    document.getElementById("claim-item-form").reset();
    
    claimModal.style.display = "block";
}

function handlePostSubmit(event) {
    event.preventDefault(); 
    
    const newItem = {
        id: Date.now(), // Use timestamp as unique ID
        type: document.getElementById("form-type").value,
        title: document.getElementById("item-title").value,
        category: document.getElementById("item-category").value,
        location: document.getElementById("item-location").value,
        date: new Date().toISOString(),
        description: document.getElementById("item-description").value,
        hiddenDetail: document.getElementById("item-hidden-detail").value,
        // who posted this item (finder)
        postedBy: currentUserEmail || 'anonymous',
        // Create a dynamic placeholder image URL based on the title
        imageUrl: `https://placehold.co/600x400/EEE/AAA?text=${encodeURIComponent(document.getElementById("item-title").value).replace(/%20/g, '+')}`,
        status: "unclaimed"
    };

    // Add new item to the front of the array
    allItems.unshift(newItem);
    
    saveItemsToStorage();
    
    // Re-render both feeds to show the new item immediately
    renderItems(allItems, itemFeedContainer);
    renderQuickFeed(allItems, quickFeedContainer);
    
    postModal.style.display = "none";
    
    showCustomAlert("Success", `Your ${newItem.type} item, '${newItem.title}', has been posted successfully!`);
}

function handleClaimSubmit(event) {
    event.preventDefault();
    
    const itemId = document.getElementById("claim-item-id").value;
    const verificationInput = document.getElementById("claim-verification").value;
    
    const item = allItems.find(i => i.id == itemId);
    
    if (!item) {
        showCustomAlert("Error", "Item not found.");
        return;
    }
    
    // Simple case-insensitive match for simulation
    if (verificationInput.toLowerCase().trim() === item.hiddenDetail.toLowerCase().trim()) {
        
        item.status = "verification";
        // record who claimed it (the finder who claims ownership)
        item.claimantEmail = currentUserEmail || 'anonymous';
        
        saveItemsToStorage();
        renderItems(allItems, itemFeedContainer);
        renderQuickFeed(allItems, quickFeedContainer);
        
        claimModal.style.display = "none";
        
        showCustomAlert("Claim Submitted", `Success! Your claim for '${item.title}' has been submitted for founder verification. You will be notified shortly.`);

    } else {
        showCustomAlert("Verification Failed", "The detail you provided does not match the hidden verification detail. Please try again or contact the admin.");
    }
}

// --- ADMIN ACTIONS ---
function verifyAndReturn(event) {
    const itemId = event.target.dataset.id;
    const item = allItems.find(i => i.id == itemId);
    if (!item) return;

    // Only allow if current user is founder/admin
    if (!isFounder) {
        showCustomAlert('Permission Denied', 'Only the founder/admin can verify and return items.');
        return;
    }

    // Ask for confirmation before performing the action
    const claimant = item.claimantEmail || item.postedBy || 'the owner';
    showCustomAlert('Confirm Return', `Verify and return "${item.title}" to ${claimant}?`, () => {
        item.status = 'returned';
        item.returnedBy = currentUserEmail || 'founder';
        item.returnedAt = new Date().toISOString();

        saveItemsToStorage();
        renderItems(allItems, itemFeedContainer);
        renderQuickFeed(allItems, quickFeedContainer);

        showCustomAlert('Item Returned', `Item '${item.title}' has been marked as returned.`);
    });
}
