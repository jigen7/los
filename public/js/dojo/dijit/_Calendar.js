/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["dijit._Calendar"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._Calendar"] = true;
dojo.provide("dijit._Calendar");

dojo.require("dojo.cldr.supplemental");
dojo.require("dojo.date");
dojo.require("dojo.date.locale");

dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

dojo.declare(
	"dijit._Calendar",
	[dijit._Widget, dijit._Templated],
	{
	// summary:
	//		A simple GUI for choosing a date in the context of a monthly calendar.
	//
	// description:
	//		A simple GUI for choosing a date in the context of a monthly calendar.
	//		This widget is used internally by other widgets and is not accessible
	//		as a standalone widget.
	//		This widget can't be used in a form because it doesn't serialize the date to an
	//		`<input>` field.  For a form element, use dijit.form.DateTextBox instead.
	//
	//		Note that the parser takes all dates attributes passed in the
	//		[RFC 3339 format](http://www.faqs.org/rfcs/rfc3339.html), e.g. `2005-06-30T08:05:00-07:00`
	//		so that they are serializable and locale-independent.
	//
	// example:
	//	|	var calendar = new dijit._Calendar({}, dojo.byId("calendarNode"));
	//
	//	example:
	//	|	<div dojoType="dijit._Calendar"></div>

		templateString:"<table cellspacing=\"0\" cellpadding=\"0\" class=\"dijitCalendarContainer\">\r\n\t<thead>\r\n\t\t<tr class=\"dijitReset dijitCalendarMonthContainer\" valign=\"top\">\r\n\t\t\t<th class='dijitReset' dojoAttachPoint=\"decrementMonth\">\r\n\t\t\t\t<img src=\"${_blankGif}\" alt=\"\" class=\"dijitCalendarIncrementControl dijitCalendarDecrease\" waiRole=\"presentation\">\r\n\t\t\t\t<span dojoAttachPoint=\"decreaseArrowNode\" class=\"dijitA11ySideArrow\">-</span>\r\n\t\t\t</th>\r\n\t\t\t<th class='dijitReset' colspan=\"5\">\r\n\t\t\t\t<div dojoAttachPoint=\"monthLabelSpacer\" class=\"dijitCalendarMonthLabelSpacer\"></div>\r\n\t\t\t\t<div dojoAttachPoint=\"monthLabelNode\" class=\"dijitCalendarMonthLabel\"></div>\r\n\t\t\t</th>\r\n\t\t\t<th class='dijitReset' dojoAttachPoint=\"incrementMonth\">\r\n\t\t\t\t<img src=\"${_blankGif}\" alt=\"\" class=\"dijitCalendarIncrementControl dijitCalendarIncrease\" waiRole=\"presentation\">\r\n\t\t\t\t<span dojoAttachPoint=\"increaseArrowNode\" class=\"dijitA11ySideArrow\">+</span>\r\n\t\t\t</th>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t\t<th class=\"dijitReset dijitCalendarDayLabelTemplate\"><span class=\"dijitCalendarDayLabel\"></span></th>\r\n\t\t</tr>\r\n\t</thead>\r\n\t<tbody dojoAttachEvent=\"onclick: _onDayClick, onmouseover: _onDayMouseOver, onmouseout: _onDayMouseOut\" class=\"dijitReset dijitCalendarBodyContainer\">\r\n\t\t<tr class=\"dijitReset dijitCalendarWeekTemplate\">\r\n\t\t\t<td class=\"dijitReset dijitCalendarDateTemplate\"><span class=\"dijitCalendarDateLabel\"></span></td>\r\n\t\t</tr>\r\n\t</tbody>\r\n\t<tfoot class=\"dijitReset dijitCalendarYearContainer\">\r\n\t\t<tr>\r\n\t\t\t<td class='dijitReset' valign=\"top\" colspan=\"7\">\r\n\t\t\t\t<h3 class=\"dijitCalendarYearLabel\">\r\n\t\t\t\t\t<span dojoAttachPoint=\"previousYearLabelNode\" class=\"dijitInline dijitCalendarPreviousYear\"></span>\r\n\t\t\t\t\t<span dojoAttachPoint=\"currentYearLabelNode\" class=\"dijitInline dijitCalendarSelectedYear\"></span>\r\n\t\t\t\t\t<span dojoAttachPoint=\"nextYearLabelNode\" class=\"dijitInline dijitCalendarNextYear\"></span>\r\n\t\t\t\t</h3>\r\n\t\t\t</td>\r\n\t\t</tr>\r\n\t</tfoot>\r\n</table>\t\r\n",

		// value: Date
		//		The currently selected Date
		value: new Date(),

		// dayWidth: String
		//		How to represent the days of the week in the calendar header. See dojo.date.locale
		dayWidth: "narrow",

		setValue: function(/*Date*/ value){
			// summary:
			//      Deprecated.   Used attr('value', ...) instead.
			// tags:
			//      deprecated
			dojo.deprecated("dijit.Calendar:setValue() is deprecated.  Use attr('value', ...) instead.", "", "2.0");
			this.attr('value', value);
		},

		_getValueAttr: function(/*String*/ value){
			// summary:
			//		Hook so attr('value') works.
			var value = new Date(this.value);
			value.setHours(0, 0, 0, 0); // return midnight, local time for back-compat

			// If daylight savings pushes midnight to the previous date, fix the Date
			// object to point at 1am so it will represent the correct day. See #9366
			if(value.getDate() < this.value.getDate()){
				value = dojo.date.add(value, "hour", 1);
			}
			return value;
		},

		_setValueAttr: function(/*Date*/ value){
			// summary:
			//		Hook to make attr("value", ...) work.
			// description:
			// 		Set the current date and update the UI.  If the date is disabled, the selection will
			//		not change, but the display will change to the corresponding month.
			// tags:
			//      protected
			if(!this.value || dojo.date.compare(value, this.value)){
				value = new Date(value);
				value.setHours(1); // to avoid issues when DST shift occurs at midnight, see #8521, #9366
				this.displayMonth = new Date(value);
				if(!this.isDisabledDate(value, this.lang)){
					this.value = value;
					this.onChange(this.attr('value'));
				}
				this._populateGrid();
			}
		},

		_setText: function(node, text){
			// summary:
			//		This just sets the content of node to the specified text.
			//		Can't do "node.innerHTML=text" because of an IE bug w/tables, see #3434.
			// tags:
			//      private
			while(node.firstChild){
				node.removeChild(node.firstChild);
			}
			node.appendChild(dojo.doc.createTextNode(text));
		},

		_populateGrid: function(){
			// summary:
			//      Fills in the calendar grid with each day (1-31)
			// tags:
			//      private
			var month = this.displayMonth;
			month.setDate(1);
			var firstDay = month.getDay();
			var daysInMonth = dojo.date.getDaysInMonth(month);
			var daysInPreviousMonth = dojo.date.getDaysInMonth(dojo.date.add(month, "month", -1));
			var today = new Date();
			var selected = this.value;

			var dayOffset = dojo.cldr.supplemental.getFirstDayOfWeek(this.lang);
			if(dayOffset > firstDay){ dayOffset -= 7; }

			// Iterate through dates in the calendar and fill in date numbers and style info
			dojo.query(".dijitCalendarDateTemplate", this.domNode).forEach(function(template, i){
				i += dayOffset;
				var date = new Date(month);
				var number, clazz = "dijitCalendar", adj = 0;

				if(i < firstDay){
					number = daysInPreviousMonth - firstDay + i + 1;
					adj = -1;
					clazz += "Previous";
				}else if(i >= (firstDay + daysInMonth)){
					number = i - firstDay - daysInMonth + 1;
					adj = 1;
					clazz += "Next";
				}else{
					number = i - firstDay + 1;
					clazz += "Current";
				}

				if(adj){
					date = dojo.date.add(date, "month", adj);
				}
				date.setDate(number);

				if(!dojo.date.compare(date, today, "date")){
					clazz = "dijitCalendarCurrentDate " + clazz;
				}

				if(!dojo.date.compare(date, selected, "date")){
					clazz = "dijitCalendarSelectedDate " + clazz;
				}

				if(this.isDisabledDate(date, this.lang)){
					clazz = "dijitCalendarDisabledDate " + clazz;
				}

				var clazz2 = this.getClassForDate(date, this.lang);
				if(clazz2){
					clazz = clazz2 + " " + clazz;
				}

				template.className =  clazz + "Month dijitCalendarDateTemplate";
				template.dijitDateValue = date.valueOf();
				var label = dojo.query(".dijitCalendarDateLabel", template)[0];
				this._setText(label, date.getDate());
			}, this);

			// Fill in localized month name
			var monthNames = dojo.date.locale.getNames('months', 'wide', 'standAlone', this.lang);
			this._setText(this.monthLabelNode, monthNames[month.getMonth()]);

			// Fill in localized prev/current/next years
			var y = month.getFullYear() - 1;
			var d = new Date();
			dojo.forEach(["previous", "current", "next"], function(name){
				d.setFullYear(y++);
				this._setText(this[name+"YearLabelNode"],
					dojo.date.locale.format(d, {selector:'year', locale:this.lang}));
			}, this);

			// Set up repeating mouse behavior
			var _this = this;
			var typematic = function(nodeProp, dateProp, adj){
				_this._connects.push(
					dijit.typematic.addMouseListener(_this[nodeProp], _this, function(count){
						if(count >= 0){ _this._adjustDisplay(dateProp, adj); }
					}, 0.8, 500)
				);
			};
			typematic("incrementMonth", "month", 1);
			typematic("decrementMonth", "month", -1);
			typematic("nextYearLabelNode", "year", 1);
			typematic("previousYearLabelNode", "year", -1);
		},

		goToToday: function(){
			// summary:
			//      Sets calendar's value to today's date
			this.attr('value', new Date());
		},

		postCreate: function(){
			this.inherited(arguments);
			dojo.setSelectable(this.domNode, false);

			var cloneClass = dojo.hitch(this, function(clazz, n){
				var template = dojo.query(clazz, this.domNode)[0];
	 			for(var i=0; i<n; i++){
					template.parentNode.appendChild(template.cloneNode(true));
				}
			});

			// clone the day label and calendar day templates 6 times to make 7 columns
			cloneClass(".dijitCalendarDayLabelTemplate", 6);
			cloneClass(".dijitCalendarDateTemplate", 6);

			// now make 6 week rows
			cloneClass(".dijitCalendarWeekTemplate", 5);

			// insert localized day names in the header
			var dayNames = dojo.date.locale.getNames('days', this.dayWidth, 'standAlone', this.lang);
			var dayOffset = dojo.cldr.supplemental.getFirstDayOfWeek(this.lang);
			dojo.query(".dijitCalendarDayLabel", this.domNode).forEach(function(label, i){
				this._setText(label, dayNames[(i + dayOffset) % 7]);
			}, this);

			// Fill in spacer element with all the month names (invisible) so that the maximum width will affect layout
			var monthNames = dojo.date.locale.getNames('months', 'wide', 'standAlone', this.lang);
			dojo.forEach(monthNames, function(name){
				var monthSpacer = dojo.create("div", null, this.monthLabelSpacer);
				this._setText(monthSpacer, name);
			}, this);

			this.value = null;
			this.attr('value', new Date());
		},

		_adjustDisplay: function(/*String*/ part, /*int*/ amount){
			// summary:
			//      Moves calendar forwards or backwards by months or years
			// part:
			//      "month" or "year"
			// amount:
			//      Number of months or years
			// tags:
			//      private
			this.displayMonth = dojo.date.add(this.displayMonth, part, amount);
			this._populateGrid();
		},

		_onDayClick: function(/*Event*/ evt){
			// summary:
			//      Handler for when user clicks a day
			// tags:
			//      protected
			dojo.stopEvent(evt);
			for(var node = evt.target; node && !node.dijitDateValue; node = node.parentNode);
			if(node && !dojo.hasClass(node, "dijitCalendarDisabledDate")){
				this.attr('value', node.dijitDateValue);
				this.onValueSelected(this.attr('value'));
			}
		},

		_onDayMouseOver: function(/*Event*/ evt){
			// summary:
			//      Handler for when user clicks a day
			// tags:
			//      protected
			var node = evt.target;
			if(node && (node.dijitDateValue || node == this.previousYearLabelNode || node == this.nextYearLabelNode) ){
				dojo.addClass(node, "dijitCalendarHoveredDate");
				this._currentNode = node;
			}
		},

		_onDayMouseOut: function(/*Event*/ evt){
			// summary:
			//      Handler for when user clicks a day
			// tags:
			//      protected
			if(!this._currentNode){ return; }
			for(var node = evt.relatedTarget; node;){
				if(node == this._currentNode){ return; }
				try{
					node = node.parentNode;
				}catch(x){
					node = null;
				}
			}
			dojo.removeClass(this._currentNode, "dijitCalendarHoveredDate");
			this._currentNode = null;
		},

		onValueSelected: function(/*Date*/ date){
			// summary:
			//		Notification that a date cell was selected.  It may be the same as the previous value.
			// description:
			//      Used by `dijit.form._DateTimeTextBox` (and thus `dijit.form.DateTextBox`)
			//      to get notification when the user has clicked a date.
			// tags:
			//      protected
		},

		onChange: function(/*Date*/ date){
			// summary:
			//		Called only when the selected date has changed
		},

		isDisabledDate: function(/*Date*/ dateObject, /*String?*/ locale){
			// summary:
			//		May be overridden to disable certain dates in the calendar e.g. `isDisabledDate=dojo.date.locale.isWeekend`
			// tags:
			//      extension
/*=====
			return false; // Boolean
=====*/
		},

		getClassForDate: function(/*Date*/ dateObject, /*String?*/ locale){
			// summary:
			//		May be overridden to return CSS classes to associate with the date entry for the given dateObject,
			//		for example to indicate a holiday in specified locale.
			// tags:
			//      extension

/*=====
			return ""; // String
=====*/
		}
	}
);

}
