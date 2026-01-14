<template>
  <NcContent app-name="bookshelfs">
    <Nav @add-book="addBook"/>
    <NcAppContent>
        <Bookshelf ref="bookshelf"/>
    </NcAppContent>
  </NcContent>

</template>

<script>
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import Bookshelf from './components/Shelf.vue'
import Nav from "./components/Nav.vue";
import NcEmptyContent from "@nextcloud/vue/components/NcEmptyContent";
import NcContent from "@nextcloud/vue/components/NcContent";

export default {
	name: 'App',
	components: {
    NcContent,
    NcEmptyContent,
		NcAppContent,
		Bookshelf,
    Nav,
	},
  methods: {
    addBook({ title, author }) {
      this.$refs.bookshelf.addBook(title, author)
    }
  }
}
</script>

<style scoped lang="scss">
.app-content {
  height: 100vh;
  width: 100vw;
}
</style>
