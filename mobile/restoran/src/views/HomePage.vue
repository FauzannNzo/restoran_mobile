<template>
  <ion-page>
    <ion-header class="ion-no-border">
      <ion-toolbar>
        <ion-title class="brand-title">
          Rasa<span class="highlight">Nyaman</span>
        </ion-title>
        <ion-buttons slot="end">
          <ion-button class="cart-btn" @click="goToCart">
            <ion-icon :icon="cartOutline"></ion-icon>
            <span class="badge" v-if="cartTotal > 0">{{ cartTotal }}</span>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true" class="ion-padding bg-light">
      
      <div class="welcome-banner mb-3">
        <h2>Mau makan apa hari ini?</h2>
      </div>

      <div v-if="loading" class="loading-container">
        <ion-spinner name="crescent"></ion-spinner>
        <p>Mengambil menu lezat...</p>
      </div>

      <div v-else>
        <div class="category-filter mb-3">
          <ion-segment v-model="activeCategory" scrollable class="custom-segment">
            <ion-segment-button 
              v-for="kategori in uniqueCategories" 
              :key="kategori" 
              :value="kategori"
            >
              <ion-label>{{ kategori }}</ion-label>
            </ion-segment-button>
          </ion-segment>
        </div><br>

        <div class="menu-grid">
          <div v-if="filteredMenus.length === 0" class="col-span-full text-center text-muted mt-4">
            <p>Belum ada menu di kategori ini.</p>
          </div>

          <ion-card v-for="item in filteredMenus" :key="item.id" class="menu-card">
            <div class="image-container">
              <img :src="item.gambar_url" alt="Foto Makanan" @error="handleImageError" />
              <div class="price-tag">Rp {{ formatPrice(item.harga) }}</div>
            </div>

            <ion-card-header>
              <ion-card-subtitle>{{ item.kategori }}</ion-card-subtitle>
              <ion-card-title>{{ item.nama_menu }}</ion-card-title>
            </ion-card-header>

            <ion-card-content>
              <div class="action-row">
                <span class="stock-info" :class="item.stok > 0 ? 'text-success' : 'text-danger'">
                  {{ item.stok > 0 ? `Sisa: ${item.stok}` : 'Habis' }}
                </span>
                
                <ion-button 
                  size="small" 
                  shape="round" 
                  class="add-btn" 
                  :disabled="item.stok <= 0"
                  @click="openDetailModal(item)"
                >
                  <ion-icon :icon="addOutline"></ion-icon> Tambah
                </ion-button>
              </div>
            </ion-card-content>
          </ion-card>
        </div>
      </div> <div slot="fixed" class="floating-cart-wrapper" v-if="cartTotal > 0">
        <div class="floating-cart-btn" @click="goToCart">
          
          <div class="cart-info-left">
            <div class="item-count">
              <ion-icon :icon="cartOutline"></ion-icon>
              <span>{{ cartTotal }} Item</span>
            </div>
            <span class="total-price-floating">Rp {{ formatPrice(cartTotalPrice) }}</span>
          </div>
          
          <div class="cart-info-right">
            <span>Keranjang</span>
            <ion-icon :icon="chevronForwardOutline"></ion-icon>
          </div>
          
        </div>
      </div>

    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { 
  IonPage, IonHeader, IonToolbar, IonTitle, IonContent, 
  IonButton, IonButtons, IonIcon, IonCard, IonCardHeader, 
  IonCardTitle, IonCardSubtitle, IonCardContent, IonSpinner,
  modalController, toastController,
  IonSegment, IonSegmentButton, IonLabel
} from '@ionic/vue';
import { cartOutline, addOutline, chevronForwardOutline } from 'ionicons/icons';
import MenuDetailModal from '../components/MenuDetailModal.vue';

const router = useRouter();
const API_URL = 'http://127.0.0.1:8000/api/menus'; 

const menus = ref<any[]>([]);
const cart = ref<any[]>([]);
const loading = ref(true);
const activeCategory = ref('Semua');

const uniqueCategories = computed(() => {
  const allCategories = menus.value.map(item => item.kategori);
  return ['Semua', ...new Set(allCategories.filter(Boolean))];
});

const filteredMenus = computed(() => {
  if (activeCategory.value === 'Semua') return menus.value;
  return menus.value.filter(item => item.kategori === activeCategory.value);
});

// Hitung Total Item
const cartTotal = computed(() => cart.value.length);

// Hitung Total Harga
const cartTotalPrice = computed(() => {
  return cart.value.reduce((sum, item) => sum + (item.subtotal || item.harga), 0);
});

// --- FETCH DATA ---
const fetchMenus = async () => {
  try {
    const response = await axios.get(API_URL);
    if(response.data.success) {
      menus.value = response.data.data;
    }
  } catch (error) {
    console.error("Gagal ambil menu:", error);
  } finally {
    loading.value = false;
  }
};

// --- MODAL & CART LOGIC ---
const openDetailModal = async (item: any) => {
  const modal = await modalController.create({
    component: MenuDetailModal,
    componentProps: { menu: item },
    breakpoints: [0, 0.75, 1],
    initialBreakpoint: 0.75,
  });

  modal.onDidDismiss().then((result) => {
    if (result.role === 'confirm') {
      addToCart(result.data);
    }
  });

  await modal.present();
};

const addToCart = async (itemWithData: any) => {
  cart.value.push(itemWithData);
  localStorage.setItem('myCart', JSON.stringify(cart.value));
  
  const toast = await toastController.create({
    message: `${itemWithData.nama_menu} masuk keranjang!`,
    duration: 1500,
    color: 'success',
    position: 'top'
  });
  await toast.present();
};

// --- NAVIGASI & UTILS ---
const goToCart = () => {
  router.push('/cart');
};

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const handleImageError = (e: Event) => {
  (e.target as HTMLImageElement).src = 'https://via.placeholder.com/150?text=No+Image';
};

// --- LIFECYCLE ---
onMounted(() => {
  fetchMenus();
  const savedCart = localStorage.getItem('myCart');
  if (savedCart) {
    cart.value = JSON.parse(savedCart);
  }
});
</script>

<style scoped>
ion-content { --background: #f8f9fa; }
.bg-light { --background: #f8f9fa; }
.text-muted { color: #888; font-size: 14px; }
.col-span-full { grid-column: 1 / -1; }

/* NAVBAR */
ion-toolbar { --background: #ffffff; padding-top: 10px; }
.brand-title { font-weight: 800; font-size: 22px; color: #333; }
.highlight { color: #FF8C00; }
.cart-btn { font-weight: 600; color: #555; position: relative; }
.badge {
  position: absolute; top: 0; right: 0; background: red;
  color: white; border-radius: 50%; padding: 2px 6px;
  font-size: 10px; font-weight: bold;
}

/* HERO BANNER & FILTER */
.welcome-banner { padding: 10px 5px; }
.welcome-banner h2 { font-weight: 800; color: #333; margin-bottom: 5px; }
.welcome-banner p { margin: 0; }
.category-filter { margin: 0 -10px; padding: 0 10px; }
.custom-segment { --background: transparent; }
.custom-segment ion-segment-button {
  --background-checked: #FF8C00;
  --color-checked: rgb(214, 214, 214);
  --color: #000000;
  --indicator-color: transparent;
  background: white; border-radius: 20px;
  margin-right: 10px; min-width: 80px;
  font-weight: 600; border: 1px solid #eee;
}

.menu-grid {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;
  padding-bottom: 100px;
}

/* CARD STYLING */
.menu-card {
  margin: 0; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  background: white; overflow: hidden;
}
.image-container { position: relative; height: 140px; width: 100%; background-color: #eee; }
.image-container img { width: 100%; height: 100%; object-fit: cover; }
.price-tag {
  position: absolute; bottom: 8px; right: 8px; background: rgba(255, 255, 255, 0.95);
  padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 12px; color: #FF8C00;
}
ion-card-header { padding: 10px; padding-bottom: 0; }
ion-card-subtitle { font-size: 10px; text-transform: uppercase; color: #999; }
ion-card-title {
  font-size: 14px; font-weight: 700; margin-top: 2px; color: #333;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden; height: 38px;
}
.action-row { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
.stock-info { font-size: 10px; font-weight: 600; }
.add-btn { height: 30px; font-size: 10px; --box-shadow: none; --background: #FF8C00; }
.text-success { color: #28a745; }
.text-danger { color: #dc3545; }
.loading-container {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; height: 50vh; color: #888;
}

.floating-cart-wrapper {
  position: absolute;
  bottom: 20px;
  left: 20px;
  right: 20px;
  z-index: 999;
}

.floating-cart-btn {
  background: #FF8C00;
  color: white;
  border-radius: 50px; 
  padding: 12px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 8px 20px rgba(255, 140, 0, 0.4);
  cursor: pointer;
  animation: slideUp 0.3s ease-out;
}

.cart-info-left {
  display: flex;
  flex-direction: column;
}

.item-count {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  opacity: 0.9;
}

.total-price-floating {
  font-weight: 800;
  font-size: 16px;
  margin-top: 2px;
}

.cart-info-right {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: bold;
  font-size: 14px;
}

@keyframes slideUp {
  from { transform: translateY(50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>