<template>
  <NcContent app-name="bookshelfs">
    <Nav @createBook="createBook"/>
    <NcAppContent>
        <Bookshelf ref="bookshelf" @select="select"/>
    </NcAppContent>
    <Sidebar ref="sidebar" @deleteBook="deleteBook" @updateBook="updateBook"/>
  </NcContent>
</template>

<script>
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import Bookshelf from './components/Shelf.vue'
import Nav from "./components/Nav.vue";
import NcContent from "@nextcloud/vue/components/NcContent";
import Sidebar from "./components/Sidebar.vue";

export default {
	name: 'App',
	components: {
    Sidebar,
    NcContent,
		NcAppContent,
		Bookshelf,
    Nav,
	},
  methods: {
    createBook(book) {
      this.$refs.bookshelf.createBook(book);
    },
    select(book) {
      this.$refs.sidebar.select(book);
    },
    updateBook(book) {
      this.$refs.bookshelf.updateBook(book);
  },
    deleteBook(book) {
      this.$refs.bookshelf.deleteBook(book);
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
