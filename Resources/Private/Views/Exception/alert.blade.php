<style>
	.blypo-exception {
		border: 1px solid #ccc;
		border-radius: 3px;
	}
	.blypo-exception.type-error {
		border-color: #a94442;
		background: #f2dede;
	}
	.blypo-exception.type-warn {
		border-color: #f0dbb1;
		background: #faf2cc ;
	}
	.blypo-exception.type-info {
		border-color: #bce8f1;
		background: #d9edf7;
	}
	.blypo-exception-heading,
	.blypo-exception-content {
		padding: 10px 15px;
	}
	.blypo-exception-content {
		background: #fff;
		border-radius: 0px 0px 3px 3px;
	}
	.type-error .blypo-exception-heading {
		color: #a94442;
	}
</style>
<div class="blypo-exception type-{{$type}}">
	<div class="blypo-exception-heading">{{$title}}</div>
	<div class="blypo-exception-content">{{$msg}}</div>
</div>