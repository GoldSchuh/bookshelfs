<template>
  <NcContent app-name="bookshelfs">
    <MyNavigation
        @add-book="addBook"/>
    <NcAppContent>
        <Bookshelf ref="bookshelf"/>
<!--      <MyMainContent v-if="selectedNote"-->
<!--                     :note="selectedNote"-->
<!--                     @edit-note="onEditNote" />-->
<!--      <NcEmptyContent v-else-->
<!--                      :name="translate('tutorial_5', 'Select a note')">-->
<!--        <template #icon>-->
<!--          <NoteIcon :size="20" />-->
<!--        </template>-->
<!--      </NcEmptyContent>-->
    </NcAppContent>
  </NcContent>

</template>

<script>
import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import Bookshelf from './components/Shelf.vue'
import MyNavigation from "./components/MyNavigation.vue";
import NcEmptyContent from "@nextcloud/vue/components/NcEmptyContent";
import MyMainContent from "./components/MyMainContent.vue";
import NcContent from "@nextcloud/vue/components/NcContent";

export default {
	name: 'App',
	components: {
    NcContent, MyMainContent, NcEmptyContent,
		NcAppContent,
		Bookshelf,
    MyNavigation,
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
