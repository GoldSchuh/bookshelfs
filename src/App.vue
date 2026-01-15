<template>
  <NcContent app-name="bookshelfs">
    <Nav @create="create"/>
    <NcAppContent>
        <Bookshelf ref="bookshelf" @select="select"/>
    </NcAppContent>
    <Sidebar ref="sidebar"/>
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
    create({ title, author, url, file}) {
      this.$refs.bookshelf.create(title, author, url, file);
    },
    select(book) {
      this.$refs.sidebar.select(book);
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
