<template>
  <NcAppSidebar v-if="open" name="" @close="onClose">
      <div v-if="selected!==null">
        <Form ref="updateBook"/>
        <NcButton
            :text="translate('bookshelfs', 'Update book')" @click="updateBook"
            variant="primary"
        />
        <NcButton
            :text="translate('bookshelfs', 'Delete book')" @click="deleteBook"
            variant="error"
        />
        <div class="side cover" :style="{ backgroundImage: `url(${getPath(selected)})` }"/>
      </div>
    </NcAppSidebar>
</template>

<script lang="ts">
import NcAppSidebarTab from  '@nextcloud/vue/components/NcAppSidebarTab'
import NcAppSidebar from  '@nextcloud/vue/components/NcAppSidebar'
import Form from "./Form.vue";
import {getPath} from '../utils.ts';
import NcAppNavigationNew from "@nextcloud/vue/components/NcAppNavigationNew";
import {NcButton} from "@nextcloud/vue";
import {translate} from "@nextcloud/l10n";
import {type Book} from "../models/Book.ts";

export default {
  components: {
    NcAppNavigationNew,
    Form,
    NcAppSidebarTab,
    NcAppSidebar,
    NcButton,
  },
  data() {
    return {
      open: false,
      selected: null as unknown as Book,
    }
  },

  methods: {
    translate,
    getPath,
    onClose() {
      this.open = false;
    },
    select(book: Book) {
      this.selected = book
      this.open = true
      this.$nextTick(() => { // Render after mount
        const update: any = this.$refs.updateBook;
        if (update) {
          update.title = book.title;
          update.author = book.author;
          update.url = book.url;
          update.file = book.file;
          update.colour = book.colour;
          update.pattern = book.pattern;
          update.height = book.height;
        }
      })
    },
    updateBook() {
      const update: any = this.$refs.updateBook;
      this.selected.title = update.title;
      this.selected.author = update.author;
      this.selected.url = update.url;
      this.selected.file = update.file;
      this.selected.colour = update.colour;
      this.selected.pattern = update.pattern;
      this.selected.height = update.height;
      this.$emit('updateBook', this.selected);
    },
    deleteBook() {
      this.$emit('deleteBook', this.selected.id);
      this.selected = null as unknown as Book;
    }
  }
}
</script>

<style scoped lang="scss">
$color_1: black;
$color_2: gold;
$color_3: goldenrod;

.form {
  margin: 20px;
  gap: 5px;
}

.side {
  border: 2px solid var(--color-border-maxcontrast);
  border-radius: 3px;
  font-weight: bold;
  color: $color_1;
}

.cover {
  margin: 20px;
  width: 190px;
  height: 280px;
  background-size: contain;
  background-repeat: round;
  left: 50px;
}

</style>
