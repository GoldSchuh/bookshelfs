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
import NcContent from "@nextcloud/vue/components/NcContent";

export default {
	name: 'App',
	components: {
    NcContent,
		NcAppContent,
		Bookshelf,
    Nav,
	},
  methods: {
    addBook({ title, author, url, file}) {
      this.$refs.bookshelf.addBook(title, author, url, file);
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
