<template>
  <NcContent app-name="bookshelfs">
    <Nav @createBook="createBook"/>
    <NcAppContent>
        <Bookshelf ref="bookshelf" @select="select"/>
    </NcAppContent>
    <Sidebar ref="sidebar" @deleteBook="deleteBook" @updateBook="updateBook"/>
  </NcContent>
</template>

<script lang="ts">
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import Bookshelf from './components/Shelf.vue'
// @ts-ignore
import Nav from "./components/Nav.vue";
import NcContent from "@nextcloud/vue/components/NcContent";
import Sidebar from "./components/Sidebar.vue";
import type {Book} from "./models/Book.ts";

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
    createBook(book: Book) {
      // @ts-ignore
      this.$refs.bookshelf.createBook(book);
    },
    select(book: Book) {
      // @ts-ignore
      this.$refs.sidebar.select(book);
    },
    updateBook(book: Book) {
      // @ts-ignore
      this.$refs.bookshelf.updateBook(book);
  },
    deleteBook(book: Book) {
      // @ts-ignore
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
