/*madara callback for visual composer*/
function iconBoxCallbackColumns() {
	var $ = jQuery;
	$columns = $( '.wpb_vc_param_value[name=item_width]', this.$content );
	var sourceModelID = this.model.id;
	var $elementChangeWidth = $('[data-model-id="'+(sourceModelID)+'"] [data-element_type="c_iconbox_item"]');
	
	var $buttonClose = this.$content.parents('.vc_ui-panel-window-inner').find('[data-vc-ui-element="button-close"]');
	var $buttonSave = this.$content.parents('.vc_ui-panel-window-inner').find('[data-vc-ui-element="button-save"]');
	
	var defaultValue = $columns.val();
	
	function setColumnsChange(objectElms, valDefault) {
		
		if($elementChangeWidth.length==0) {
			return;
		}
		
		$elementChangeWidth.removeClass('col-extend col-extend-md-1 col-extend-md-2 col-extend-md-3 col-extend-md-4 col-extend-md-5 col-extend-md-6 col-extend-md-7 col-extend-md-8 col-extend-md-9 col-extend-md-10 col-extend-md-11 col-extend-md-12 col-extend-20percent col-extend-14percent col-extend-12percent col-extend-11percent col-extend-10percent col-extend-09percent');
		
		var $this = objectElms;
		var strVal = '';
		if($this!='') {
			strVal=$this.val();
		}else{
			strVal=valDefault;
		};		
		switch(strVal){
			case '1_12':
				$elementChangeWidth.addClass('col-extend col-extend-md-1');
				break;
			case '2_12':
				$elementChangeWidth.addClass('col-extend col-extend-md-2');
				break;
			case '3_12':
				$elementChangeWidth.addClass('col-extend col-extend-md-3');
				break;
			case '4_12':
				$elementChangeWidth.addClass('col-extend col-extend-md-4');
				break;
			case '1_5':
				$elementChangeWidth.addClass('col-extend col-extend-20percent');
				break;
			default:
				break;					
		}
	}
	
	$columns.change( function(){		
		setColumnsChange($(this),'');			
	})
	.trigger('change');
	
	$buttonClose.on('click', function(){
		if(defaultValue!=$columns.val()) {
			//check value, restore default
			setColumnsChange('', defaultValue);
		}
	});
	
	$buttonSave.on('click', function(){
		//check value, set new default
		defaultValue = $columns.val();
	});
};
(function($){
	function initColumns(elems) {
		elems.each(function(index, element) {
			var $this = $(this);
			var dataModelID = $this.attr('data-model-id');
			var $elementChangeWidth = $('[data-model-id="'+(dataModelID)+'"] [data-element_type="c_iconbox_item"]');
			
			var $itemWidth = $this.find('.admin_label_item_width');
			var itemWidthString = $itemWidth.text();
			
			if(itemWidthString!='') {
				if(itemWidthString.indexOf("1/12 (12 items)")>0) {
					$elementChangeWidth.addClass('col-extend col-extend-md-1');
				}else if(itemWidthString.indexOf("2/12 (6 items)")>0){
					$elementChangeWidth.addClass('col-extend col-extend-md-2');
				}else if(itemWidthString.indexOf("3/12 (4 items)")>0){
					$elementChangeWidth.addClass('col-extend col-extend-md-3');
				}else if(itemWidthString.indexOf("4/12 (3 items)")>0){
					$elementChangeWidth.addClass('col-extend col-extend-md-4');
				}else if(itemWidthString.indexOf("1/5 (5 items)")>0){
					$elementChangeWidth.addClass('col-extend col-extend-20percent');
				}
			};			
		});
	}
	$(document).ready(function(e) {
		$('[data-element_type="c_iconbox_item"] .vc_control.column_clone').live('click', function(){
			setTimeout(function() {
				initColumns($('[data-element_type="c_iconbox"]:not(.col-extend)'));
			},200);
		});
        $(window).on('load', function(){
			setTimeout(function() {
				initColumns($('[data-element_type="c_iconbox"]'))
			},100);
		});
    });
}(jQuery));
/*madara callback for visual composer*/