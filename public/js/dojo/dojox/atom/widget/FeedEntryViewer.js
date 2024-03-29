/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["dojox.atom.widget.FeedEntryViewer"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojox.atom.widget.FeedEntryViewer"] = true;
dojo.provide("dojox.atom.widget.FeedEntryViewer");

dojo.require("dojo.fx");
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");
dojo.require("dijit._Container");
dojo.require("dijit.layout.ContentPane");
dojo.require("dojox.atom.io.Connection");
dojo.requireLocalization("dojox.atom.widget", "FeedEntryViewer", null, "ROOT,ar,ca,cs,da,de,el,es,fi,fr,he,hu,it,ja,ko,nb,nl,pl,pt,pt-pt,ru,sk,sl,sv,th,tr,zh,zh-tw");

dojo.experimental("dojox.atom.widget.FeedEntryViewer");

dojo.declare("dojox.atom.widget.FeedEntryViewer",[dijit._Widget, dijit._Templated, dijit._Container],{
	//	summary:
	//		An ATOM feed entry editor for publishing updated ATOM entries, or viewing non-editable entries.
	//	description:
	//		An ATOM feed entry editor for publishing updated ATOM entries, or viewing non-editable entries.
	entrySelectionTopic: "",	//The topic to listen on for entries to edit.

	_validEntryFields: {},		//The entry fields that were present on the entry and are being displayed.
								//This works in conjuntion with what is selected to be displayed.
	displayEntrySections: "",	//What current sections of the entries to display as a comma separated list.
	_displayEntrySections: null,
	
	//Control options for the display options menu.
	enableMenu: false,
	enableMenuFade: false,
	_optionButtonDisplayed: true,

	//Templates for the HTML rendering.  Need to figure these out better, admittedly.
	templateString:"<div class=\"feedEntryViewer\">\r\n    <table border=\"0\" width=\"100%\" class=\"feedEntryViewerMenuTable\" dojoAttachPoint=\"feedEntryViewerMenu\" style=\"display: none;\">\r\n        <tr width=\"100%\"  dojoAttachPoint=\"entryCheckBoxDisplayOptions\">\r\n            <td align=\"right\">\r\n                <span class=\"feedEntryViewerMenu\" dojoAttachPoint=\"displayOptions\" dojoAttachEvent=\"onclick:_toggleOptions\"></span>\r\n            </td>\r\n        </tr>\r\n        <tr class=\"feedEntryViewerDisplayCheckbox\" dojoAttachPoint=\"entryCheckBoxRow\" width=\"100%\" style=\"display: none;\">\r\n            <td dojoAttachPoint=\"feedEntryCelltitle\">\r\n                <input type=\"checkbox\" name=\"title\" value=\"Title\" dojoAttachPoint=\"feedEntryCheckBoxTitle\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelTitle\"></label>\r\n            </td>\r\n            <td dojoAttachPoint=\"feedEntryCellauthors\">\r\n                <input type=\"checkbox\" name=\"authors\" value=\"Authors\" dojoAttachPoint=\"feedEntryCheckBoxAuthors\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelAuthors\"></label>\r\n            </td>\r\n            <td dojoAttachPoint=\"feedEntryCellcontributors\">\r\n                <input type=\"checkbox\" name=\"contributors\" value=\"Contributors\" dojoAttachPoint=\"feedEntryCheckBoxContributors\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelContributors\"></label>\r\n            </td>\r\n            <td dojoAttachPoint=\"feedEntryCellid\">\r\n                <input type=\"checkbox\" name=\"id\" value=\"Id\" dojoAttachPoint=\"feedEntryCheckBoxId\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelId\"></label>\r\n            </td>\r\n            <td rowspan=\"2\" align=\"right\">\r\n                <span class=\"feedEntryViewerMenu\" dojoAttachPoint=\"close\" dojoAttachEvent=\"onclick:_toggleOptions\"></span>\r\n            </td>\r\n\t\t</tr>\r\n\t\t<tr class=\"feedEntryViewerDisplayCheckbox\" dojoAttachPoint=\"entryCheckBoxRow2\" width=\"100%\" style=\"display: none;\">\r\n            <td dojoAttachPoint=\"feedEntryCellupdated\">\r\n                <input type=\"checkbox\" name=\"updated\" value=\"Updated\" dojoAttachPoint=\"feedEntryCheckBoxUpdated\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelUpdated\"></label>\r\n            </td>\r\n            <td dojoAttachPoint=\"feedEntryCellsummary\">\r\n                <input type=\"checkbox\" name=\"summary\" value=\"Summary\" dojoAttachPoint=\"feedEntryCheckBoxSummary\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelSummary\"></label>\r\n            </td>\r\n            <td dojoAttachPoint=\"feedEntryCellcontent\">\r\n                <input type=\"checkbox\" name=\"content\" value=\"Content\" dojoAttachPoint=\"feedEntryCheckBoxContent\" dojoAttachEvent=\"onclick:_toggleCheckbox\"/>\r\n\t\t\t\t<label for=\"title\" dojoAttachPoint=\"feedEntryCheckBoxLabelContent\"></label>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n    \r\n    <table class=\"feedEntryViewerContainer\" border=\"0\" width=\"100%\">\r\n        <tr class=\"feedEntryViewerTitle\" dojoAttachPoint=\"entryTitleRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entryTitleHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entryTitleNode\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n\r\n        <tr class=\"feedEntryViewerAuthor\" dojoAttachPoint=\"entryAuthorRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entryAuthorHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entryAuthorNode\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n\r\n        <tr class=\"feedEntryViewerContributor\" dojoAttachPoint=\"entryContributorRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entryContributorHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entryContributorNode\" class=\"feedEntryViewerContributorNames\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n        \r\n        <tr class=\"feedEntryViewerId\" dojoAttachPoint=\"entryIdRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entryIdHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entryIdNode\" class=\"feedEntryViewerIdText\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    \r\n        <tr class=\"feedEntryViewerUpdated\" dojoAttachPoint=\"entryUpdatedRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entryUpdatedHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entryUpdatedNode\" class=\"feedEntryViewerUpdatedText\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    \r\n        <tr class=\"feedEntryViewerSummary\" dojoAttachPoint=\"entrySummaryRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entrySummaryHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entrySummaryNode\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    \r\n        <tr class=\"feedEntryViewerContent\" dojoAttachPoint=\"entryContentRow\" style=\"display: none;\">\r\n            <td>\r\n                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\r\n                    <tr class=\"graphic-tab-lgray\">\r\n\t\t\t\t\t\t<td class=\"lp2\">\r\n\t\t\t\t\t\t\t<span class=\"lp\" dojoAttachPoint=\"entryContentHeader\"></span>\r\n\t\t\t\t\t\t</td>\r\n                    </tr>\r\n                    <tr>\r\n                        <td dojoAttachPoint=\"entryContentNode\">\r\n                        </td>\r\n                    </tr>\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>\r\n</div>\r\n",
	
	_entry: null,			//The entry that is being viewed/edited.
	_feed:  null,			//The feed the entry came from.

	_editMode: false,		//Flag denoting the state of the widget, in edit mode or not.
	
	postCreate: function(){
		if(this.entrySelectionTopic !== ""){
			this._subscriptions = [dojo.subscribe(this.entrySelectionTopic, this, "_handleEvent")];
		}
		var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
		this.displayOptions.innerHTML = _nlsResources.displayOptions;
		this.feedEntryCheckBoxLabelTitle.innerHTML = _nlsResources.title;
		this.feedEntryCheckBoxLabelAuthors.innerHTML = _nlsResources.authors;
		this.feedEntryCheckBoxLabelContributors.innerHTML = _nlsResources.contributors;
		this.feedEntryCheckBoxLabelId.innerHTML = _nlsResources.id;
		this.close.innerHTML = _nlsResources.close;
		this.feedEntryCheckBoxLabelUpdated.innerHTML = _nlsResources.updated;
		this.feedEntryCheckBoxLabelSummary.innerHTML = _nlsResources.summary;
		this.feedEntryCheckBoxLabelContent.innerHTML = _nlsResources.content;
	},

	startup: function(){
		if(this.displayEntrySections === ""){
			this._displayEntrySections = ["title","authors","contributors","summary","content","id","updated"];
		}else{
			this._displayEntrySections = this.displayEntrySections.split(",");
		}
		this._setDisplaySectionsCheckboxes();

		if(this.enableMenu){
			dojo.style(this.feedEntryViewerMenu, 'display', '');
			if(this.entryCheckBoxRow && this.entryCheckBoxRow2){
				if(this.enableMenuFade){
					dojo.fadeOut({node: this.entryCheckBoxRow,duration: 250}).play();
					dojo.fadeOut({node: this.entryCheckBoxRow2,duration: 250}).play();
				}
			}
		}
	},

	clear: function(){
		//	summary:
		//		Function to clear the state of the widget.
		//	description:
		//		Function to clear the state of the widget.
		this.destroyDescendants();
		this._entry=null;
		this._feed=null;
		this.clearNodes();
	},
	
	clearNodes: function(){
		//	summary:
		//		Function to clear all the display nodes for the ATOM entry from the viewer.
		//	description:
		//		Function to clear all the display nodes for the ATOM entry from the viewer.

		dojo.forEach([
			"entryTitleRow", "entryAuthorRow", "entryContributorRow", "entrySummaryRow", "entryContentRow", 
			"entryIdRow", "entryUpdatedRow"
			], function(node){
				dojo.style(this[node], "display", "none");
			}, this);

		dojo.forEach([
			"entryTitleNode", "entryTitleHeader", "entryAuthorHeader", "entryContributorHeader",
			"entryContributorNode", "entrySummaryHeader", "entrySummaryNode", "entryContentHeader",
			"entryContentNode", "entryIdNode", "entryIdHeader", "entryUpdatedHeader", "entryUpdatedNode"
			], function(part){
				while(this[part].firstChild){
                    dojo.destroy(this[part].firstChild);
				}
			}
		,this);
		
	},

	setEntry: function(/*object*/entry, /*object*/feed, /*boolean*/leaveMenuState){
		//	summary:
		//		Function to set the current entry that is being edited.
		//	description:
		//		Function to set the current entry that is being edited.
		//
		//	entry:
		//		Instance of dojox.atom.io.model.Entry to display for reading/editing.
		this.clear();
		this._validEntryFields = {};
		this._entry = entry;
		this._feed = feed;

		if(entry !== null){
			// Handle the title.
			if(this.entryTitleHeader){
				this.setTitleHeader(this.entryTitleHeader, entry);
			}
			
			if(this.entryTitleNode){
				this.setTitle(this.entryTitleNode, this._editMode, entry);
			}

			if(this.entryAuthorHeader){
				this.setAuthorsHeader(this.entryAuthorHeader, entry);
			}

			if(this.entryAuthorNode){
				this.setAuthors(this.entryAuthorNode, this._editMode, entry);
			}
			
			if(this.entryContributorHeader){
				this.setContributorsHeader(this.entryContributorHeader, entry);
			}

			if(this.entryContributorNode){
				this.setContributors(this.entryContributorNode, this._editMode, entry);
			}

			if(this.entryIdHeader){
				this.setIdHeader(this.entryIdHeader, entry);
			}

			if(this.entryIdNode){
				this.setId(this.entryIdNode, this._editMode, entry);
			}

			if(this.entryUpdatedHeader){
				this.setUpdatedHeader(this.entryUpdatedHeader, entry); 
			}

			if(this.entryUpdatedNode){
				this.setUpdated(this.entryUpdatedNode, this._editMode, entry); 
			}

			if(this.entrySummaryHeader){
				this.setSummaryHeader(this.entrySummaryHeader, entry); 
			}

			if(this.entrySummaryNode){
				this.setSummary(this.entrySummaryNode, this._editMode, entry); 
			}

			if(this.entryContentHeader){
				this.setContentHeader(this.entryContentHeader, entry); 
			}

			if(this.entryContentNode){
				this.setContent(this.entryContentNode, this._editMode, entry); 
			}
		}
		this._displaySections();
	},

	setTitleHeader: function(/*DOM node*/titleHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the title header node in the template to some value.
		//	description:
		//		Function to set the contents of the title header node in the template to some value.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	titleAchorNode:
		//		The DOM node to attach the title data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		//
		if(entry.title && entry.title.value && entry.title.value !== null){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var titleHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.title});
			titleHeaderNode.appendChild(titleHeader.domNode);
		}
	},

	setTitle: function(titleAnchorNode, editMode, entry){
		//	summary:
		//		Function to set the contents of the title node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the title node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	titleAchorNode:
		//		The DOM node to attach the title data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.title && entry.title.value && entry.title.value !== null){
			if(entry.title.type == "text"){
				var titleNode = document.createTextNode(entry.title.value);
				titleAnchorNode.appendChild(titleNode);
			} else {
				var titleViewNode = document.createElement("span");
				var titleView = new dijit.layout.ContentPane({refreshOnShow: true, executeScripts: false}, titleViewNode);
				titleView.attr('content', entry.title.value);
				titleAnchorNode.appendChild(titleView.domNode);
			}
			this.setFieldValidity("title", true);
		}
	},

	setAuthorsHeader: function(/*DOM node*/authorHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the title format for the authors section of the author row in the template to some value from the entry.
		//	description:
		//		Function to set the title format for the authors section of the author row in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the author data is filled out from an entry.
		//
		//	authorHeaderNode:
		//		The DOM node to attach the author section header data to.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.authors && entry.authors.length > 0){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var authorHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.authors});
			authorHeaderNode.appendChild(authorHeader.domNode);
		}
	},

	setAuthors: function(/*DOM node*/authorsAnchorNode, /*boolean*/editMode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the author node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the author node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	authorsAchorNode:
		//		The DOM node to attach the author data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.authors && entry.authors.length > 0){
			for (var i in entry.authors){
				if(entry.authors[i].name){
					var anchor = authorsAnchorNode;
					if(entry.authors[i].uri){
						var link = document.createElement("a");
						anchor.appendChild(link);
						link.href = entry.authors[i].uri;
						anchor = link;
					}
					var name = entry.authors[i].name;
					if(entry.authors[i].email){
						name = name + " (" + entry.authors[i].email + ")";
					}
					var authorNode = document.createTextNode(name);
					anchor.appendChild(authorNode);
					var breakNode = document.createElement("br");
					authorsAnchorNode.appendChild(breakNode);
					this.setFieldValidity("authors", true);
				}
			}
		}
	},

	setContributorsHeader: function(/*DOM node*/contributorsHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the contributor header node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the contributor header node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	contributorsHeaderNode:
		//		The DOM node to attach the contributor title to.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.contributors && entry.contributors.length > 0){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var contributorHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.contributors});
			contributorsHeaderNode.appendChild(contributorHeader.domNode);
		}
	},


	setContributors: function(/*DOM node*/contributorsAnchorNode, /*boolean*/editMode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the contributor node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the contributor node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	contributorsAnchorNode:
		//		The DOM node to attach the contributor data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.contributors && entry.contributors.length > 0){
			for (var i in entry.contributors){
				var contributorNode = document.createTextNode(entry.contributors[i].name);
				contributorsAnchorNode.appendChild(contributorNode);
				var breakNode = document.createElement("br");
				contributorsAnchorNode.appendChild(breakNode);
				this.setFieldValidity("contributors", true);
			}
		}
	},

				 
	setIdHeader: function(/*DOM node*/idHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the ID  node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the ID node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	idAnchorNode:
		//		The DOM node to attach the ID data to.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.id && entry.id !== null){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var idHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.id});
			idHeaderNode.appendChild(idHeader.domNode);
		}
	},


	setId: function(/*DOM node*/idAnchorNode, /*boolean*/editMode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the ID  node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the ID node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	idAnchorNode:
		//		The DOM node to attach the ID data to.
		// 	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.id && entry.id !== null){
			var idNode = document.createTextNode(entry.id);
			idAnchorNode.appendChild(idNode);
			this.setFieldValidity("id", true);
		}
	},
	
	setUpdatedHeader: function(/*DOM node*/updatedHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the updated header node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the updated header node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	updatedHeaderNode:
		//		The DOM node to attach the updated header data to.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.updated && entry.updated !== null){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var updatedHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.updated});
			updatedHeaderNode.appendChild(updatedHeader.domNode);
		}
	},

	setUpdated: function(/*DOM node*/updatedAnchorNode, /*boolean*/editMode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the updated  node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the updated node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	updatedAnchorNode:
		//		The DOM node to attach the udpated data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.updated && entry.updated !== null){
			var updatedNode = document.createTextNode(entry.updated);
			updatedAnchorNode.appendChild(updatedNode);
			this.setFieldValidity("updated", true);
		}
	},

	setSummaryHeader: function(/*DOM node*/summaryHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the summary  node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the summary node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	summaryHeaderNode:
		//		The DOM node to attach the summary title to.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.summary && entry.summary.value && entry.summary.value !== null){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var summaryHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.summary});
			summaryHeaderNode.appendChild(summaryHeader.domNode);
		}
	},


	setSummary: function(/*DOM node*/summaryAnchorNode, /*boolean*/editMode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the summary  node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the summary node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	summaryAnchorNode:
		//		The DOM node to attach the summary data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.summary && entry.summary.value && entry.summary.value !== null){
			var summaryViewNode = document.createElement("span");
			var summaryView = new dijit.layout.ContentPane({refreshOnShow: true, executeScripts: false}, summaryViewNode);
			summaryView.attr('content', entry.summary.value);
			summaryAnchorNode.appendChild(summaryView.domNode);
			this.setFieldValidity("summary", true);
		}
	},

	setContentHeader: function(/*DOM node*/contentHeaderNode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the content node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the content node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	contentHeaderNode:
		//		The DOM node to attach the content data to.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.content && entry.content.value && entry.content.value !== null){
			var _nlsResources = dojo.i18n.getLocalization("dojox.atom.widget", "FeedEntryViewer");
			var contentHeader = new dojox.atom.widget.EntryHeader({title: _nlsResources.content});
			contentHeaderNode.appendChild(contentHeader.domNode);
		}
	},

	setContent: function(/*DOM node*/contentAnchorNode, /*boolean*/editMode, /*object*/entry){
		//	summary:
		//		Function to set the contents of the content node in the template to some value from the entry.
		//	description:
		//		Function to set the contents of the content node in the template to some value from the entry.
		//		This exists specifically so users can over-ride how the title data is filled out from an entry.
		//
		//	contentAnchorNode:
		//		The DOM node to attach the content data to.
		//	editMode:
		//		Boolean to indicate if the display should be in edit mode or not.
		//	entry:
		//		The Feed Entry to work with.
		if(entry.content && entry.content.value && entry.content.value !== null){
			var contentViewNode = document.createElement("span");
			var contentView = new dijit.layout.ContentPane({refreshOnShow: true, executeScripts: false},contentViewNode);
			contentView.attr('content', entry.content.value);
			contentAnchorNode.appendChild(contentView.domNode);
			this.setFieldValidity("content", true);
		}
	},


	_displaySections: function(){
		//	summary:
		//		Internal function for determining which sections of the view to actually display.
		//	description:
		//		Internal function for determining which sections of the view to actually display.
		//
		//	returns:
		//		Nothing. 
		dojo.style(this.entryTitleRow, 'display', 'none');
		dojo.style(this.entryAuthorRow, 'display', 'none');
		dojo.style(this.entryContributorRow, 'display', 'none');
		dojo.style(this.entrySummaryRow, 'display', 'none');
		dojo.style(this.entryContentRow, 'display', 'none');
		dojo.style(this.entryIdRow, 'display', 'none');
		dojo.style(this.entryUpdatedRow, 'display', 'none');

		for (var i in this._displayEntrySections){
			var section = this._displayEntrySections[i].toLowerCase();
			if(section === "title" && this.isFieldValid("title")){
				dojo.style(this.entryTitleRow, 'display', '');
			}
			if(section === "authors" && this.isFieldValid("authors")){
				dojo.style(this.entryAuthorRow, 'display', '');
			}
			if(section === "contributors" && this.isFieldValid("contributors")){
				dojo.style(this.entryContributorRow, 'display', '');
			}
			if(section === "summary" && this.isFieldValid("summary")){
				dojo.style(this.entrySummaryRow, 'display', '');
			}
			if(section === "content" && this.isFieldValid("content")){
				dojo.style(this.entryContentRow, 'display', '');
			}
			if(section === "id" && this.isFieldValid("id")){
				dojo.style(this.entryIdRow, 'display', '');
			}
			if(section === "updated" && this.isFieldValid("updated")){
				dojo.style(this.entryUpdatedRow, 'display', '');
			}

		}
	},

	setDisplaySections: function(/*array*/sectionsArray){
		//	summary:
		//		Function for setting which sections of the entry should be displayed.
		//	description:
		//		Function for setting which sections of the entry should be displayed.
		//
		//	sectionsArray:
		//		Array of string names that indicate which sections to display.
		//
		//	returns:
		//		Nothing.
		if(sectionsArray !== null){
			this._displayEntrySections = sectionsArray;
			this._displaySections();
		}
		else {
			this._displayEntrySections = ["title","authors","contributors","summary","content","id","updated"];
		}
	},

	_setDisplaySectionsCheckboxes: function(){
		//	summary:
		//		Internal function for setting which checkboxes on the display are selected.
		//	description:
		//		Internal function for setting which checkboxes on the display are selected.
		//
		//	returns:
		//		Nothing.
		var items = ["title","authors","contributors","summary","content","id","updated"];
		for(var i in items){
			if(dojo.indexOf(this._displayEntrySections, items[i])==-1){
				dojo.style(this["feedEntryCell"+items[i]], 'display', 'none');
			}else{
				this["feedEntryCheckBox"+items[i].substring(0,1).toUpperCase()+items[i].substring(1)].checked=true;
			}
		}
	},

	_readDisplaySections: function(){
		//	summary:
		//		Internal function for reading what is currently checked for display and generating the display list from it.
		//	description:
		//		Internal function for reading what is currently checked for display and generating the display list from it.
		//
		//	returns:
		//		Nothing.
		var checkedList = [];

		if(this.feedEntryCheckBoxTitle.checked){
			checkedList.push("title");
		}
		if(this.feedEntryCheckBoxAuthors.checked){
			checkedList.push("authors");
		}
		if(this.feedEntryCheckBoxContributors.checked){
			checkedList.push("contributors");
		}
		if(this.feedEntryCheckBoxSummary.checked){
			checkedList.push("summary");
		}
		if(this.feedEntryCheckBoxContent.checked){
			checkedList.push("content");
		}
		if(this.feedEntryCheckBoxId.checked){
			checkedList.push("id");
		}
		if(this.feedEntryCheckBoxUpdated.checked){
			checkedList.push("updated");
		}
		this._displayEntrySections = checkedList;
	},

	_toggleCheckbox: function(/*object*/checkBox){
		//	summary:
		//		Internal function for determining of a particular entry is editable.
		//	description:
		//		Internal function for determining of a particular entry is editable.
		//		This is used for determining if the delete action should be displayed or not.
		//
		//	checkBox:
		//		The checkbox object to toggle the selection on.
		//
		//	returns:
		//		Nothing
		if(checkBox.checked){
			checkBox.checked=false;
		}
		else {
			checkBox.checked=true;
		}
		this._readDisplaySections();
		this._displaySections();
	},

	_toggleOptions: function(/*object*/checkBox){
		//	summary:
		//		Internal function for determining of a particular entry is editable.
		//	description:
		//		Internal function for determining of a particular entry is editable.
		//		This is used for determining if the delete action should be displayed or not.
		//
		//	checkBox:
		//		The checkbox object to toggle the selection on.
		//
		//	returns:
		//		Nothing
		if(this.enableMenu){
			var fade = null;
			var anim;
			var anim2;
			if(this._optionButtonDisplayed){
				if(this.enableMenuFade){
					anim = dojo.fadeOut({node: this.entryCheckBoxDisplayOptions,duration: 250});
					dojo.connect(anim, "onEnd", this, function(){
						dojo.style(this.entryCheckBoxDisplayOptions, 'display', 'none');
						dojo.style(this.entryCheckBoxRow, 'display', '');
						dojo.style(this.entryCheckBoxRow2, 'display', '');
						dojo.fadeIn({node: this.entryCheckBoxRow, duration: 250}).play();
						dojo.fadeIn({node: this.entryCheckBoxRow2, duration: 250}).play();
					});
					anim.play();
				}else{
					dojo.style(this.entryCheckBoxDisplayOptions, 'display', 'none');
					dojo.style(this.entryCheckBoxRow, 'display', '');
					dojo.style(this.entryCheckBoxRow2, 'display', '');
				}
				this._optionButtonDisplayed=false;
			}else{
				if(this.enableMenuFade){
					anim = dojo.fadeOut({node: this.entryCheckBoxRow,duration: 250});
					anim2 = dojo.fadeOut({node: this.entryCheckBoxRow2,duration: 250});
					dojo.connect(anim, "onEnd", this, function(){
						dojo.style(this.entryCheckBoxRow, 'display', 'none');
						dojo.style(this.entryCheckBoxRow2, 'display', 'none');
						dojo.style(this.entryCheckBoxDisplayOptions, 'display', '');
						dojo.fadeIn({node: this.entryCheckBoxDisplayOptions, duration: 250}).play();
					});
					anim.play();
					anim2.play();
				}else{
					dojo.style(this.entryCheckBoxRow, 'display', 'none');
					dojo.style(this.entryCheckBoxRow2, 'display', 'none');
					dojo.style(this.entryCheckBoxDisplayOptions, 'display', '');
				}
				this._optionButtonDisplayed=true;
			}
		}
	},

	_handleEvent: function(/*object*/entrySelectionEvent){
		//	summary:
		//		Internal function for listening to a topic that will handle entry notification.
		//	description:
		//		Internal function for listening to a topic that will handle entry notification.
		//
		//	entrySelectionEvent:
		//		The topic message containing the entry that was selected for view.
		//
		//	returns:
		//		Nothing.
		if(entrySelectionEvent.source != this){
			if(entrySelectionEvent.action == "set" && entrySelectionEvent.entry){
				this.setEntry(entrySelectionEvent.entry, entrySelectionEvent.feed);
			}else if(entrySelectionEvent.action == "delete" && entrySelectionEvent.entry && entrySelectionEvent.entry == this._entry){
				this.clear();
			}
		}
	},

	setFieldValidity: function(/*string*/field, /*boolean*/isValid){
		//	summary:
		//		Function to set whether a field in the view is valid and displayable.
		//	description:
		//		Function to set whether a field in the view is valid and displayable.
		//		This is needed for over-riding of the set* functions and customization of how data is displayed in the attach point.
		//		So if custom implementations use their own display logic, they can still enable the field.
		//
		//	field:
		//		The field name to set the valid parameter on.  Such as 'content', 'id', etc.
		//	isValid:
		//		Flag denoting if the field is valid or not.
		//
		//	returns:
		//		Nothing.
		if(field){
			var lowerField = field.toLowerCase();
			this._validEntryFields[field] = isValid;
		}
	},
	
	isFieldValid: function(/*string*/field){
		//	summary:
		//		Function to return if a displayable field is valid or not
		//	description:
		//		Function to return if a displayable field is valid or not
		//
		//	field:
		//		The field name to get the valid parameter of.  Such as 'content', 'id', etc.
		//
		//	returns:
		//		boolean denoting if the field is valid and set.
		return this._validEntryFields[field.toLowerCase()];
	},

	getEntry: function(){
		return this._entry;
	},

	getFeed: function(){
		 return this._feed;
	},

	destroy: function(){
		this.clear();
		dojo.forEach(this._subscriptions, dojo.unsubscribe);
	}
});

dojo.declare("dojox.atom.widget.EntryHeader",[dijit._Widget, dijit._Templated, dijit._Container],{
	//	summary:
	//		Widget representing a header in a FeedEntryViewer/Editor
	//	description:
	//		Widget representing a header in a FeedEntryViewer/Editor
	title: "",
	templateString:"<span dojoAttachPoint=\"entryHeaderNode\" class=\"entryHeaderNode\"></span>\r\n",

	postCreate: function(){
		this.setListHeader();
	},

	setListHeader: function(/*string*/title){
		this.clear();
		if(title){
			this.title = title;
		}
		var textNode = document.createTextNode(this.title);
		this.entryHeaderNode.appendChild(textNode);
	},

	clear: function(){
		this.destroyDescendants();
		 if(this.entryHeaderNode){
			 for (var i = 0; i < this.entryHeaderNode.childNodes.length; i++){
				 this.entryHeaderNode.removeChild(this.entryHeaderNode.childNodes[i]);
			 }
		 }
	},

	destroy: function(){
		this.clear();
	}
});

}
