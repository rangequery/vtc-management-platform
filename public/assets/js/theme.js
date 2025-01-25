// (function() {
//   "use strict"; // Start of use strict
//
//   var sidebar = document.querySelector('.sidebar');
//   var sidebarToggles = document.querySelectorAll('#sidebarToggle, #sidebarToggleTop');
//
//   if (sidebar) {
//
//     var collapseEl = sidebar.querySelector('.collapse');
//     var collapseElementList = [].slice.call(document.querySelectorAll('.sidebar .collapse'))
//     var sidebarCollapseList = collapseElementList.map(function (collapseEl) {
//       return new bootstrap.Collapse(collapseEl, { toggle: false });
//     });
//
//     for (var toggle of sidebarToggles) {
//
//       // Toggle the side navigation
//       toggle.addEventListener('click', function(e) {
//         document.body.classList.toggle('sidebar-toggled');
//         sidebar.classList.toggle('toggled');
//
//         if (sidebar.classList.contains('toggled')) {
//           for (var bsCollapse of sidebarCollapseList) {
//             bsCollapse.hide();
//           }
//         };
//       });
//     }
//
//     // Close any open menu accordions when window is resized below 768px
//     window.addEventListener('resize', function() {
//       var vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
//
//       if (vw < 768) {
//         for (var bsCollapse of sidebarCollapseList) {
//           bsCollapse.hide();
//         }
//       };
//     });
//   }
//
//   // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
//
//   var fixedNaigation = document.querySelector('body.fixed-nav .sidebar');
//
//   if (fixedNaigation) {
//     fixedNaigation.on('mousewheel DOMMouseScroll wheel', function(e) {
//       var vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
//
//       if (vw > 768) {
//         var e0 = e.originalEvent,
//           delta = e0.wheelDelta || -e0.detail;
//         this.scrollTop += (delta < 0 ? 1 : -1) * 30;
//         e.preventDefault();
//       }
//     });
//   }
//
//   var scrollToTop = document.querySelector('.scroll-to-top');
//
//   if (scrollToTop) {
//
//     // Scroll to top button appear
//     window.addEventListener('scroll', function() {
//       var scrollDistance = window.pageYOffset;
//
//       //check if user is scrolling up
//       if (scrollDistance > 100) {
//         scrollToTop.style.display = 'block';
//       } else {
//         scrollToTop.style.display = 'none';
//       }
//     });
//   }
//
// })(); // End of use strict
(function() {
  "use strict"; // Start of use strict

  var sidebar = document.querySelector('.sidebar');
  var sidebarToggles = document.querySelectorAll('#sidebarToggle, #sidebarToggleTop');

  if (sidebar) {

    var collapseEl = sidebar.querySelector('.collapse');
    var collapseElementList = [].slice.call(document.querySelectorAll('.sidebar .collapse'));
    var sidebarCollapseList = collapseElementList.map(function (collapseEl) {
      return new bootstrap.Collapse(collapseEl, { toggle: false });
    });

    // Fonction pour vérifier la largeur de la fenêtre
    function checkWindowWidth() {
      var vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
      if (vw < 768) {
        sidebar.classList.add('toggled'); // Toujours réduire la barre latérale
        for (var bsCollapse of sidebarCollapseList) {
          bsCollapse.hide();
        }
      } else {
        sidebar.classList.remove('toggled'); // Retirer la classe 'toggled' pour les écrans plus larges
      }
    }

    // Vérifier la largeur de la fenêtre au chargement
    checkWindowWidth();

    // Vérifier la largeur de la fenêtre lors du redimensionnement
    window.addEventListener('resize', checkWindowWidth);

    for (var toggle of sidebarToggles) {
      // Toggle the side navigation
      toggle.addEventListener('click', function(e) {
        document.body.classList.toggle('sidebar-toggled');
        sidebar.classList.toggle('toggled');

        if (sidebar.classList.contains('toggled')) {
          for (var bsCollapse of sidebarCollapseList) {
            bsCollapse.hide();
          }
        }
      });
    }

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    var fixedNaigation = document.querySelector('body.fixed-nav .sidebar');

    if (fixedNaigation) {
      fixedNaigation.addEventListener('mousewheel', function(e) {
        var vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);

        if (vw > 768) {
          var e0 = e.originalEvent,
              delta = e0.wheelDelta || -e0.detail;
          this.scrollTop += (delta < 0 ? 1 : -1) * 30;
          e.preventDefault();
        }
      });
    }

    var scrollToTop = document.querySelector('.scroll-to-top');

    if (scrollToTop) {
      // Scroll to top button appear
      window.addEventListener('scroll', function() {
        var scrollDistance = window.pageYOffset;

        // Check if user is scrolling up
        if (scrollDistance > 100) {
          scrollToTop.style.display = 'block';
        } else {
          scrollToTop.style.display = 'none';
        }
      });
    }
  }

})(); // End of use strict