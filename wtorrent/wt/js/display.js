var DisplayActions = Class.create({
	/* Use scriptaculous or not */
	initialize: function(effects) {
		if(effects == null)
		{
			this.effects = true;
		} else {
			this.effects = effects;
		}
	},
	/* Toggle torrent frame */
	toggleTorrent: function(id) {
		if(this.effects)
		{
			new Effect.toggle('ttab' + id, 'blind', {duration:0.5});
		} else {
			$('ttab' + id).toggle();
		}
	},
	/* Open torrent info */
	openTorrent: function(id) {
		if(this.effects)
		{
			new Effect.BlindDown('ttab' + id, {duration:0.5});
		} else {
			$('ttab' + id).show();
		}
	},
	/* Close torrent info */
	closeTorrent: function(id) {
		if(this.effects)
		{
			new Effect.BlindUp('ttab' + id, {duration:0.5});
		} else {
			$('ttab' + id).hide();
		}
	}
});
