<template>
  <NcAppSidebar v-if="open" name="" @close="onClose">
      <div v-if="selected!==null">
        <Form ref="updateBook"/>
        <NcButton
            aria-label="Example text"

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
// @ts-ignore
import IconMagnify from 'vue-material-design-icons/Magnify.vue'
// @ts-ignore
import IconCogOutline from 'vue-material-design-icons/CogOutline.vue'
// @ts-ignore
import IconShareVariantOutline from 'vue-material-design-icons/ShareVariantOutline.vue'
import NcAppSidebarTab from  '@nextcloud/vue/components/NcAppSidebarTab'
import NcAppSidebar from  '@nextcloud/vue/components/NcAppSidebar'
// @ts-ignore
import Form from "./Form.vue";
import {getPath} from '../utils.ts';
import NcAppNavigationNew from "@nextcloud/vue/components/NcAppNavigationNew";
import {NcButton} from "@nextcloud/vue";
import {translate} from "@nextcloud/l10n";
import {generateOcsUrl} from "@nextcloud/router";
import axios from "@nextcloud/axios";
import {type Book, constructBook} from "../models/Book.ts";
import {showError} from "@nextcloud/dialogs";

export default {
  name: 'Sidebar',
  components: {
    NcAppNavigationNew,
    Form,
    IconMagnify,
    IconCogOutline,
    IconShareVariantOutline,
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
      const options = {
        id: this?.selected.id,
        title: update.title,
        author: update.author,
        url: update.url,
        file: update.file,
        colour: update.colour,
        pattern: update.pattern,
        height: update.height,
      }
      const api = generateOcsUrl('apps/bookshelfs/api/v1/books/' + this.selected.id)
      axios.put(api, options).then(response => {
        this.selected = constructBook(response.data.ocs.data)
        this.$emit('updateBook', this.selected);
      }).catch((error) => {
        showError(translate('bookshelfs', 'Error updating book'))
        console.error(error)
      })
    },
    deleteBook() {
      const options = {
        id: this.selected.id
      }
      const api = generateOcsUrl('apps/bookshelfs/api/v1/books/' + this.selected.id)
      // @ts-ignore
      axios.delete(api, options).then(() => {
        this.$emit('deleteBook', this.selected);
        this.selected = null as unknown as Book;
      }).catch((error) => {
        showError(translate('bookshelfs', 'Error deleting book'))
        console.error(error)
      })
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
  //display: flex;
  //flex-direction: column;
  //flex-wrap: ;
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
  //top: 0;
  background-size: contain;
  background-repeat: round;
  left: 50px;
  //transform: rotateY(90deg) translateZ(0);
  //transition: transform 1s;
}

</style>
