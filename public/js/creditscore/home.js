	$(document).ready(function() { $("#myTable").tablesorter(); } );
	
	Event.observe(window, 'load', loadAccordions, false);
	function loadAccordions() {
			var topAccordion = new accordion('horizontal_container', {
				classNames : {
					toggle : 'horizontal_accordion_toggle',
					toggleActive : 'horizontal_accordion_toggle_active',
					content : 'horizontal_accordion_content'
				},
				defaultSize : {
					width : 525
				},
				direction : 'horizontal'
			});
			
			var bottomAccordion = new accordion('vertical_container');
			
			var nestedVerticalAccordion = new accordion('vertical_nested_container', {
			  classNames : {
					toggle : 'vertical_accordion_toggle',
					toggleActive : 'vertical_accordion_toggle_active',
					content : 'vertical_accordion_content'
				}
			});
			
			bottomAccordion.activate($$('#vertical_container .accordion_toggle')[0]);
			
			topAccordion.activate($$('#horizontal_container .horizontal_accordion_toggle')[2]);
		}
		
		var verticalAccordions = $$('.accordion_toggle');
        verticalAccordions.each(function(accordion) {
            $(accordion.next(0)).setStyle({
              height: '0px'
            });
        });		