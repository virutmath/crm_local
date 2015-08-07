TestHelpers.commonWidgetTests( "droppable", {
	defaults: {
		accept: "*",
		activeClass: false,
		addClasses: true,
		classes: {},
		disabled: false,
		greedy: false,
		hoverClass: false,
		scope: "default",
		tolerance: "intersect",

		// callbacks
		activate: null,
		create: null,
		deactivate: null,
		drop: null,
		out: null,
		over: null
	}
});
