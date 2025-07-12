export const layout = () => {
    return {
        sidebarOpen: true,
        init() {
            this.sidebarOpen = localStorage.getItem("sidebarOpen") === "true";
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem("sidebarOpen", this.sidebarOpen);
            if (!isMobileDevice) {
            }
        },
        toggleSidebarMobile() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeSidebarOnMobile() {
            if (window.innerWidth < 768) {
                this.sidebarOpen = false;
            }
        },
    };
};

function isMobileDevice() {
    return /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
}

export const dataTable = (data) => {
    return {
        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: data,
        selectedRow: null,

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortColumn = column;
                this.sortAsc = true;
            }
        },

        filteredData() {
            let temp = this.rows.filter((row) =>
                Object.values(row).some((val) =>
                    String(val)
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                )
            );

            temp.sort((a, b) => {
                let valA = a[this.sortColumn];
                let valB = b[this.sortColumn];

                if (typeof valA === "string") valA = valA.toLowerCase();
                if (typeof valB === "string") valB = valB.toLowerCase();

                if (valA < valB) return this.sortAsc ? -1 : 1;
                if (valA > valB) return this.sortAsc ? 1 : -1;
                return 0;
            });

            return temp;
        },

        paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredData().slice(start, start + this.perPage);
        },

        totalPages() {
            return Math.ceil(this.filteredData().length / this.perPage);
        },

        nextPage() {
            if (this.currentPage < this.totalPages()) this.currentPage++;
        },

        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        rupiah(number) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(number);
        },

        selectData(row) {
            console.log(row);
            this.selectedRow = row;
        },

        dateParse(isoDate) {
            const date = new Date(isoDate);
            const humanReadable = date.toLocaleString("id-ID", {
                weekday: "long", // Sabtu
                year: "numeric", // 2025
                month: "long", // Juli
                day: "numeric", // 12
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                // timeZoneName: "short", // misalnya "WIB"
            });
            return humanReadable;
        },
    };
};

export function imageCropper() {
    return {
        imageUrl: null,
        cropper: null,

        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.imageUrl = URL.createObjectURL(file);

            this.$nextTick(() => {
                const image = document.getElementById("preview");
                if (this.cropper) this.cropper.destroy();
                this.cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: true,
                    aspectRatio: 1,
                    zoomable: false,
                    rotatable: false,
                    cropBoxResizable: false,
                    background: false,
                    ready() {
                        const cropper = this.cropper;
                        const containerData = cropper.getContainerData();
                        cropper.setCropBoxData({
                            width: 200,
                            height: 200,
                            left: (containerData.width - 200) / 2,
                            top: (containerData.height - 200) / 2,
                        });
                    },
                });
            });
        },

        cropImage() {
            if (!this.cropper) return;

            this.cropper.getCroppedCanvas().toBlob((blob) => {
                // Buat file untuk dikirim ke server
                const file = new File([blob], "cropped.png", {
                    type: "image/png",
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.croppedInput.files = dataTransfer.files;

                // Tampilkan hasil crop sebagai preview
                const croppedUrl = URL.createObjectURL(blob);
                this.imageUrl = croppedUrl;

                // Hancurkan cropper dan bersihkan
                this.cropper.destroy();
                this.cropper = null;
            }, "image/png");
        },
    };
}

export const dataSelect = (data) => {
    console.log(data);
    return {
        open: false,
        search: "",
        selected: null,
        options: data,
        get filteredOptions() {
            return this.options.filter((option) =>
                option.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        selectItem(item) {
            this.selected = item;
            this.search = item.name;
            this.open = false;
        },
    };
};

export function posApp() {
    return {
        cart: [],
        discount: 0,
        takeawayFee: 0,
        pending: [],

        get subtotal() {
            return (
                this.cart.reduce(
                    (total, item) => total + item.price * item.qty,
                    0
                ) - this.discount
            );
        },
        get serviceCharge() {
            return Math.round(this.subtotal * 0);
        },
        get total() {
            return this.subtotal + this.serviceCharge + this.takeawayFee;
        },

        addToCart(product) {
            console.log(product);
            let existing = this.cart.find((i) => i.id === product.id);
            product.stok = parseInt(product.stok); // pastikan stok berupa angka

            if (existing) {
                if (existing.qty + 1 > product.stok) {
                    alert(`Stok untuk ${product.name} tidak mencukupi!`);
                    return;
                }
                existing.qty += 1;
            } else {
                if (product.stok < 1) {
                    alert(`Stok untuk ${product.name} habis!`);
                    return;
                }
                this.cart.push({ ...product, qty: 1 });
            }
        },

        clearCart() {
            this.cart = [];
        },

        refreshPending() {
            this.pending = JSON.parse(
                localStorage.getItem("pendingCarts") || "[]"
            );
        },

        savePending() {
            const name = prompt("Masukkan nama transaksi pending:");
            if (!name) return;

            let pendings = JSON.parse(
                localStorage.getItem("pendingCarts") || "[]"
            );
            pendings.push({ name, items: this.cart });
            localStorage.setItem("pendingCarts", JSON.stringify(pendings));
            this.clearCart();
            this.refreshPending();
        },

        getPendingList() {
            return JSON.parse(localStorage.getItem("pendingCarts") || "[]");
        },

        deletePending(name) {
            let pendings = JSON.parse(
                localStorage.getItem("pendingCarts") || "[]"
            );
            pendings = pendings.filter((p) => p.name !== name);
            localStorage.setItem("pendingCarts", JSON.stringify(pendings));
            this.refreshPending();
        },

        loadPendingByName(name) {
            let pendings = JSON.parse(
                localStorage.getItem("pendingCarts") || "[]"
            );
            const pending = pendings.find((p) => p.name === name);
            if (pending) {
                this.cart = pending.items;
                pendings = pendings.filter((p) => p.name !== name);
                localStorage.setItem("pendingCarts", JSON.stringify(pendings));
                this.refreshPending();
            } else {
                alert("Transaksi tidak ditemukan.");
            }
        },

        removeCart(id) {
            this.cart = this.cart.filter((item) => item.id !== id);
        },

        rupiah(number) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(number);
        },

        async submitCart() {
            console.log(JSON.stringify(this.cart));
            try {
                const response = await fetch("/dashboard/transaksi", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify(this.cart),
                });

                const data = await response.json();

                if (response.ok) {
                    this.cart = [];
                    this.successMessage = "Form berhasil dikirim!";
                } else {
                    console.error(
                        "Gagal:",
                        data.message || "Terjadi kesalahan"
                    );
                }
            } catch (error) {
                console.error("Error:", error);
            }
        },

        printBill() {
            const printContent = this.cart
                .map(
                    (item) => `
        <tr>
            <td>${item.name}</td>
            <td>${item.qty}</td>
            <td>${this.rupiah(item.price)}</td>
            <td>${this.rupiah(item.price * item.qty)}</td>
        </tr>
    `
                )
                .join("");

            const html = `
        <html>
            <head>
                <title>Bill</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                    h2 { text-align: center; }
                </style>
            </head>
            <body>
                <h2>Struk Pembayaran</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${printContent}
                    </tbody>
                </table>
                <h3>Total: ${this.rupiah(this.total)}</h3>
                <script>
                    window.onload = function () {
                        window.print();
                        setTimeout(() => window.close(), 100);
                    };
                </script>
            </body>
        </html>
    `;

            const win = window.open("", "_blank");
            win.document.write(html);
            win.document.close();
        },
    };
}

export function formHandler() {
    return {
        form: {
            name: "",
        },
        errors: {},
        success: false,

        async submitForm(actionUrl) {
            console.log(JSON.stringify(this.form));
            this.errors = {};
            this.success = false;

            try {
                const response = await fetch(actionUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify(this.form),
                });

                if (!response.ok) {
                    if (response.status === 422) {
                        const res = await response.json();
                        this.errors = res.errors || {};
                    } else {
                        throw new Error("Gagal mengirim data.");
                    }
                    return;
                }

                this.form.name = "";
                this.success = true;
            } catch (error) {
                alert(error.message);
            }
        },
    };
}
