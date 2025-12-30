<template>
	<NcAppContent>
		<div id="bookshelfs">
			<Bookshelf/>
		</div>
	</NcAppContent>
</template>

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
.app-content {
  height: 100vh;
  width: 100vw;
}
</style>
