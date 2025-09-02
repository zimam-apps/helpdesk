// Apply 'minimenu' early (before DOMContentLoaded)
if (localStorage.getItem('minimenu-enabled') === 'true') {
  document.body.classList.add('no-transition');
  document.body.classList.add('minimenu');
}

// Inject style to disable transitions on load
const style = document.createElement('style');
style.textContent = `
  body.no-transition .dash-sidebar,
  body.no-transition .main-content {
    transition: none !important;
  }
`;
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const body = document.body;
  const sidebarSubmenu = document.querySelector('.sidebar-submenu');
  let activeDashItem = null;

  // Remove no-transition after load
  if (body.classList.contains('no-transition')) {
    setTimeout(() => {
      body.classList.remove('no-transition');
    }, 50);
  }

  // Function to reset all submenus
  function resetAllSubmenus() {
    document.querySelectorAll('.dash-submenu').forEach(submenu => {
      const parent = submenu.parentElement;
      if (parent && parent.classList.contains('active')) {
        // Keep submenu visible for active items and don't move them
        if (!body.classList.contains('minimenu')) {
          submenu.style.display = 'block';
          parent.classList.add('dash-trigger');
        }
      } else {
        submenu.style.display = 'none';
        if (parent) {
          parent.classList.remove('dash-trigger');
          parent.classList.remove('dash-active');
        }
      }
    });
    
    // Don't clear sidebar submenu content, just hide it
    if (sidebarSubmenu) {
      sidebarSubmenu.style.display = 'none';
    }
    activeDashItem = null;
  }

  // Initialize submenu states
  function initializeSubmenus() {
    document.querySelectorAll('.navbar-content .dash-item.dash-hasmenu').forEach(item => {
      const submenu = item.querySelector(':scope > .dash-submenu');
      if (submenu) {
        // Ensure submenu is in the correct position
        if (submenu.parentElement !== item) {
          item.appendChild(submenu);
        }
        
        // Check if item has dash-trigger, dash-active, or active class and open submenu by default
        if (item.classList.contains('dash-trigger') || item.classList.contains('dash-active') || item.classList.contains('active')) {
          submenu.style.display = 'block';
          // Always add dash-trigger class if item has active class and not in minimenu mode
          if (item.classList.contains('active') && !body.classList.contains('minimenu')) {
            item.classList.add('dash-trigger');
          }
        } else {
          submenu.style.display = 'none';
          item.classList.remove('dash-trigger');
          item.classList.remove('dash-active');
        }
      }
    });
  }

  // Function to ensure active items show submenu in normal menu
  function ensureActiveSubmenus() {
    if (!body.classList.contains('minimenu')) {
      setTimeout(() => {
        document.querySelectorAll('.dash-item.dash-hasmenu.active').forEach(item => {
          let submenu = item.querySelector('.dash-submenu');
          
          // If submenu not found in item, check if it's in sidebar
          if (!submenu && sidebarSubmenu) {
            submenu = sidebarSubmenu.querySelector('.dash-submenu');
            if (submenu) {
              // Move submenu back to original item
              item.appendChild(submenu);
            }
          }
          
          if (submenu) {
            // Ensure submenu is in the correct position
            if (submenu.parentElement !== item) {
              item.appendChild(submenu);
            }
            submenu.style.display = 'block';
            item.classList.add('dash-trigger');
          }
        });
      }, 100);
    }
  }

  // Toggle minimenu on button click
  toggleBtn.addEventListener('click', function () {
    const enabled = body.classList.contains('minimenu');
    body.classList.toggle('minimenu', !enabled);

    if (!enabled) {
      localStorage.setItem('minimenu-enabled', 'true');
    } else {
      localStorage.removeItem('minimenu-enabled');
    }

    // Get the currently active item (either in normal or minimenu mode)
    let activeItem = document.querySelector('.dash-item.dash-trigger');
    if (!activeItem) {
      activeItem = document.querySelector('.dash-item.dash-active');
    }
    if (!activeItem) {
      activeItem = document.querySelector('.dash-item.active');
    }

    if (activeItem) {
      let submenu = activeItem.querySelector('.dash-submenu');
      
      // If submenu not found in activeItem, check sidebar
      if (!submenu && sidebarSubmenu) {
        submenu = sidebarSubmenu.querySelector('.dash-submenu');
      }
      
      if (submenu) {
        // First, reset all submenus
        resetAllSubmenus();
        
        if (body.classList.contains('minimenu')) {
          // Switch to minimenu mode
          activeItem.classList.add('dash-active');
          // Ensure submenu is moved to sidebar and displayed
          sidebarSubmenu.innerHTML = '';
          sidebarSubmenu.appendChild(submenu);
          submenu.style.display = 'block';
          sidebarSubmenu.style.display = 'block';
          activeDashItem = activeItem;
          
          requestAnimationFrame(() => {
            positionSidebarSubmenu(activeItem);
          });
        } else {
          // Switch to normal mode - move submenu back to original position
          if (sidebarSubmenu && sidebarSubmenu.contains(submenu)) {
            activeItem.appendChild(submenu);
          }
          if (activeItem.classList.contains('active')) {
            activeItem.classList.add('dash-trigger');
          }
          submenu.style.display = 'block';
          // Force the submenu to be visible
          setTimeout(() => {
            submenu.style.display = 'block';
            if (activeItem.classList.contains('active')) {
              activeItem.classList.add('dash-trigger');
            }
          }, 10);
        }
      }
    } else {
      // No active item, just reset
      resetAllSubmenus();
    }

    // Ensure active submenus are shown in normal menu
    ensureActiveSubmenus();
  });

  // Position submenu beside item
  function positionSidebarSubmenu(target) {
    const rect = target.getBoundingClientRect();
    const submenuHeight = sidebarSubmenu.offsetHeight;
    const viewportHeight = window.innerHeight;
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';
    let top = rect.top + rect.height;
    const left = isRTL
      ? window.innerWidth - rect.left + 10
      : rect.left + rect.width + 10;

    if (top + submenuHeight > viewportHeight) {
      const availableAbove = rect.top;
      if (availableAbove > submenuHeight) {
        top = rect.top - submenuHeight;
      } else {
        top = Math.min(viewportHeight - submenuHeight - 10, rect.top + rect.height);
        sidebarSubmenu.style.maxHeight = `${viewportHeight - top - 20}px`;
        sidebarSubmenu.style.overflowY = 'auto';
      }
    } else {
      sidebarSubmenu.style.maxHeight = '';
      sidebarSubmenu.style.overflowY = '';
    }

    sidebarSubmenu.style.position = 'fixed';
    sidebarSubmenu.style.top = `${top}px`;

    if (isRTL) {
      sidebarSubmenu.style.right = `${left}px`;
      sidebarSubmenu.style.left = 'auto';
    } else {
      sidebarSubmenu.style.left = `${left}px`;
      sidebarSubmenu.style.right = 'auto';
    }

    sidebarSubmenu.style.zIndex = '9999';
  }

  // Attach submenu listeners
  function attachClickListeners() {
    document.querySelectorAll('.navbar-content .dash-item.dash-hasmenu').forEach(item => {
      const submenu = item.querySelector(':scope > .dash-submenu');
      if (!submenu) return;

      // Remove any existing click handlers
      const newItem = item.cloneNode(true);
      const newSubmenu = newItem.querySelector(':scope > .dash-submenu');
      item.parentNode.replaceChild(newItem, item);
      
      newItem.addEventListener('click', function(e) {
        // Only prevent default if clicking on the menu item itself, not its links
        if (e.target === this || e.target.closest('.dash-link') === this.querySelector('.dash-link')) {
          e.preventDefault();
          e.stopPropagation();

          if (body.classList.contains('minimenu')) {
            handleMinimenuClick(this, newSubmenu);
          } else {
            handleNormalMenuClick(this, newSubmenu);
          }
        }
      });

      // Add click handlers for submenu links
      if (newSubmenu) {
        newSubmenu.querySelectorAll('a').forEach(link => {
          link.addEventListener('click', function(e) {
            // Don't prevent default for links
            e.stopPropagation();
          });
        });
      }
    });
  }

  function handleNormalMenuClick(item, submenu) {
    // Close all other submenus at the same level
    const parent = item.parentElement;
    const siblings = parent.querySelectorAll('.dash-submenu');
    siblings.forEach(sib => {
      if (sib !== submenu) {
        sib.style.display = 'none';
        sib.parentElement.classList.remove('dash-trigger');
      }
    });

    // Toggle current submenu
    if (submenu.style.display === 'block') {
      submenu.style.display = 'none';
      item.classList.remove('dash-trigger');
    } else {
      // First ensure the submenu is in the correct position
      if (submenu.parentElement !== item) {
        item.appendChild(submenu);
      }
      submenu.style.display = 'block';
      item.classList.add('dash-trigger');
    }
  }

  function handleMinimenuClick(item, submenu) {
    const isSameItem = activeDashItem === item;

    if (isSameItem && submenu.style.display === 'block') {
      submenu.style.display = 'none';
      sidebarSubmenu.style.display = 'none';
      item.classList.remove('dash-active');
      activeDashItem = null;
      return;
    }

    // Close any previously open submenu
    if (activeDashItem && activeDashItem !== item) {
      const oldSubmenu = sidebarSubmenu.querySelector('.dash-submenu');
      if (oldSubmenu) {
        activeDashItem.appendChild(oldSubmenu);
        oldSubmenu.style.display = 'none';
      }
      activeDashItem.classList.remove('dash-active');
    }

    item.classList.add('dash-active');
    sidebarSubmenu.innerHTML = '';
    sidebarSubmenu.appendChild(submenu);
    submenu.style.display = 'block';
    sidebarSubmenu.style.display = 'block';

    requestAnimationFrame(() => {
      positionSidebarSubmenu(item);
    });

    activeDashItem = item;
  }

  // Handle class changes on body
  const observer = new MutationObserver(() => {
    if (!body.classList.contains('minimenu')) {
      resetAllSubmenus();
    }
  });

  observer.observe(body, { attributes: true, attributeFilter: ['class'] });

  // Initialize and attach listeners
  initializeSubmenus();
  attachClickListeners();

  // Close submenu when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dash-sidebar') && !e.target.closest('.sidebar-submenu')) {
      resetAllSubmenus();
    }
  });

  // Sidebar close logic
  document.addEventListener("click", function (e) {
    const closeBtn = e.target.closest(".sidebar-close-btn");
    const sidebar = document.querySelector(".dash-sidebar");

    if (closeBtn && sidebar) {
      const overlay = sidebar.querySelector(".dash-menu-overlay");
      if (overlay) {
        overlay.remove();
      }
      sidebar.classList.remove("dash-over-menu-active");
      sidebar.classList.remove("mob-sidebar-active");
      body.classList.remove("no-scroll");
      body.classList.remove("mob-sidebar-active");
      resetAllSubmenus();
    }
  });
});