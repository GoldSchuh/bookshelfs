<template>
	<NcAppContent>
		<div id="bookshelfs">
			<Bookshelf/>
		</div>
	</NcAppContent>
</template>

<!--FIXME propr css for vue3-->
<!--FIXME lower #nr of filewatchers again?-->


<script>
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import Bookshelf from './Shelf.vue'

export default {
	name: 'App',
	components: {
		NcAppContent,
		Bookshelf,
	},
}
</script>

<style scoped lang="scss">
#bookshelfs {
	display: flex;
	justify-content: center;
	margin: 16px;
}
</style>
