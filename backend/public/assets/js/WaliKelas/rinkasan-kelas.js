
    // Default configuration
    const defaultConfig = {
      school_name: 'SMPIT Ad Durrah',
      teacher_name: 'Ustadz Ahmad Fauzi, S.Pd',
      class_name: 'VII-A',
      primary_color: '#1F7A4D',
      secondary_color: '#E6F4EC',
      text_color: '#1f2937',
      background_color: '#f9fafb',
      accent_color: '#10b981'
    };

    // Toggle accordion
    function toggleAccordion(id) {
      const content = document.getElementById('accordion-' + id);
      const arrow = document.getElementById('arrow-' + id);
      
      content.classList.toggle('open');
      arrow.classList.toggle('open');
    }

    // Toggle sidebar for mobile
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');
      
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
    }

    // Open preview modal
    function openPreviewModal() {
      const modal = document.getElementById('previewModal');
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    // Close preview modal
    function closePreviewModal() {
      const modal = document.getElementById('previewModal');
      modal.classList.add('hidden');
      document.body.style.overflow = '';
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closePreviewModal();
      }
    });

    // Initialize Element SDK
    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange: async (config) => {
          // Update school name in sidebar
          const schoolNameSidebar = document.getElementById('schoolNameSidebar');
          if (schoolNameSidebar) {
            schoolNameSidebar.textContent = config.school_name || defaultConfig.school_name;
          }

          // Update teacher name in header
          const teacherNameHeader = document.getElementById('teacherNameHeader');
          if (teacherNameHeader) {
            teacherNameHeader.textContent = config.teacher_name || defaultConfig.teacher_name;
          }

          // Update class name in header
          const classNameHeader = document.getElementById('classNameHeader');
          if (classNameHeader) {
            const className = config.class_name || defaultConfig.class_name;
            classNameHeader.textContent = 'Wali Kelas ' + className;
          }
        },
        mapToCapabilities: (config) => ({
          recolorables: [
            {
              get: () => config.primary_color || defaultConfig.primary_color,
              set: (value) => {
                config.primary_color = value;
                window.elementSdk.setConfig({ primary_color: value });
              }
            },
            {
              get: () => config.secondary_color || defaultConfig.secondary_color,
              set: (value) => {
                config.secondary_color = value;
                window.elementSdk.setConfig({ secondary_color: value });
              }
            },
            {
              get: () => config.text_color || defaultConfig.text_color,
              set: (value) => {
                config.text_color = value;
                window.elementSdk.setConfig({ text_color: value });
              }
            },
            {
              get: () => config.background_color || defaultConfig.background_color,
              set: (value) => {
                config.background_color = value;
                window.elementSdk.setConfig({ background_color: value });
              }
            },
            {
              get: () => config.accent_color || defaultConfig.accent_color,
              set: (value) => {
                config.accent_color = value;
                window.elementSdk.setConfig({ accent_color: value });
              }
            }
          ],
          borderables: [],
          fontEditable: undefined,
          fontSizeable: undefined
        }),
        mapToEditPanelValues: (config) => new Map([
          ['school_name', config.school_name || defaultConfig.school_name],
          ['teacher_name', config.teacher_name || defaultConfig.teacher_name],
          ['class_name', config.class_name || defaultConfig.class_name]
        ])
      });
    }
  
 (function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9c76212ab3811d47',t:'MTc2OTk5Nzk4OS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();