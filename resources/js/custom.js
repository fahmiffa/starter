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
        isLoading: false,

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
            this.isLoading = true;
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
                this.isLoading = false;
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

export function Crud() {
    return {
        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: [],
        form: {
            name: "",
            email: "",
            hp: "",
            password: "",
            app: "",
            userID: "",
        },
        isEditing: false,
        errors: {},
        success: false,

        fetchData(actionUrl) {
            const method = "GET";
            fetch(actionUrl, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => (this.rows = data));
        },

        editItem(item) {
            this.form.id = item.id;
            this.form.app = item.name;
            this.form.name = item.users[0].name;
            this.form.hp = item.users[0].nomor;
            this.form.email = item.users[0].email;
            this.isEditing = true;
            this.userID = item.users[0].id;
            this.errors = {};
        },

        async formHandler(actionUrl) {
            this.errors = {};
            this.success = false;

            try {
                let url = actionUrl;
                let method = "POST";

                if (this.isEditing) {
                    url = `${actionUrl}/${this.form.id}/${this.userID}`;
                    method = "PUT";
                }

                const response = await fetch(url, {
                    method: method,
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

                this.form = {
                    name: "",
                    email: "",
                    hp: "",
                    password: "",
                    app: "",
                };
                this.success = true;
                this.fetchData(`${actionUrl}-json`);
            } catch (error) {
                alert(error.message);
            }
        },

        async deleteItem(actionUrl, id, user) {
            if (!confirm("Hapus data ini?")) return;

            await fetch(`${actionUrl}/${id}/${user}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            this.fetchData(`${actionUrl}-json`);
        },

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
    };
}

export function Crudcat() {
    return {
        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: [],
        form: {
            name: "",
            id: "",
        },
        isEditing: false,
        errors: {},
        success: false,

        fetchData(actionUrl) {
            const method = "GET";
            fetch(actionUrl, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => (this.rows = data));
        },

        editItem(item) {
            console.log(item);
            this.form.id = item.id;
            this.form.name = item.name;
            this.isEditing = true;
            this.errors = {};
        },

        async formHandler(actionUrl) {
            this.errors = {};
            this.success = false;

            try {
                let url = actionUrl;
                let method = "POST";

                if (this.isEditing) {
                    url = `${actionUrl}/${this.form.id}`;
                    method = "PUT";
                }

                const response = await fetch(url, {
                    method: method,
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

                this.form = {
                    name: "",
                };
                this.success = true;
                this.fetchData(`${actionUrl}-json`);
            } catch (error) {
                alert(error.message);
            }
        },

        async deleteItem(actionUrl, id) {
            if (!confirm("Hapus data ini?")) return;

            await fetch(`${actionUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            this.fetchData(`${actionUrl}-json`);
        },

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
    };
}

export function Crudunit() {
    return {
        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: [],
        form: {
            name: "",
            pcs: "",
            id: "",
        },
        isEditing: false,
        errors: {},
        success: false,

        fetchData(actionUrl) {
            const method = "GET";
            fetch(actionUrl, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => (this.rows = data));
        },

        editItem(item) {
            this.form.id = item.id;
            this.form.name = item.name;
            this.form.pcs = item.pcs;
            this.isEditing = true;
            this.errors = {};
        },

        async formHandler(actionUrl) {
            this.errors = {};
            this.success = false;

            try {
                let url = actionUrl;
                let method = "POST";

                if (this.isEditing) {
                    url = `${actionUrl}/${this.form.id}`;
                    method = "PUT";
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify(this.form),
                });

                this.isEditing = false;

                if (!response.ok) {
                    if (response.status === 422) {
                        const res = await response.json();
                        this.errors = res.errors || {};
                    } else {
                        throw new Error("Gagal mengirim data.");
                    }
                    return;
                }

                this.form = {
                    name: "",
                };
                this.success = true;
                this.fetchData(`${actionUrl}-json`);
            } catch (error) {
                alert(error.message);
            }
        },

        async deleteItem(actionUrl, id) {
            if (!confirm("Hapus data ini?")) return;

            await fetch(`${actionUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            this.fetchData(`${actionUrl}-json`);
        },

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
    };
}

export function Cruditem() {
    return {
        cats: [],
        searchCat: "",
        selectedCat: null,
        openCat: false,

        unit: [],
        searchUnit: "",
        selectedUnit: null,
        openUnit: false,

        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: [],
        form: {
            name: "",
            stok: "",
            price: "",
            id: "",
            cat_id: "",
            unit_id: "",
        },
        isEditing: false,
        errors: {},
        success: false,
        isLoading: false,

        fetchData(actionUrl) {
            const method = "GET";
            fetch(actionUrl, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.rows = data;
                });
        },

        fetchCat() {
            const method = "GET";
            fetch("/dashboard/categori-json", {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.cats = data;
                });
        },

        filteredCat() {
            return this.cats.filter((item) =>
                item.name.toLowerCase().includes(this.searchCat.toLowerCase())
            );
        },

        selectCat(item) {
            this.selectedCat = item.id;
            this.searchCat = item.name;
            this.openCat = false;
            this.form.cat_id = item.id;
        },

        fetchUnit() {
            const method = "GET";
            fetch("/dashboard/unit-json", {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.unit = data;
                });
        },

        filteredUnit() {
            return this.unit.filter((item) =>
                item.name.toLowerCase().includes(this.searchUnit.toLowerCase())
            );
        },

        selectUnit(item) {
            this.form.unit_id = item.id;
            this.selectedUnit = item.id;
            this.searchUnit = item.name;
            this.openUnit = false;
        },

        editItem(item) {
            console.log(item);
            this.form.id = item.id;
            this.form.name = item.name;
            this.form.stok = item.stok;
            this.form.price = item.price;
            this.isEditing = true;
            this.errors = {};
        },

        async formHandler(actionUrl) {
            console.log(this.form);
            this.isLoading = true;
            this.errors = {};
            this.success = false;

            try {
                let url = actionUrl;
                let method = "POST";

                if (this.isEditing) {
                    url = `${actionUrl}/${this.form.id}`;
                    method = "PUT";
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify(this.form),
                });

                this.isEditing = false;

                if (!response.ok) {
                    if (response.status === 422) {
                        const res = await response.json();
                        this.errors = res.errors || {};
                        this.isLoading = false;
                    } else {
                        throw new Error("Gagal mengirim data.");
                    }
                    return;
                }

                this.form = {
                    name: "",
                    stok: "",
                    price: "",
                    cat_id: "",
                    unit_id: "",
                };
                this.selectCat = null;
                this.selectUnit = null;
                this.success = false;
                this.fetchData(`${actionUrl}-json`);
                this.isLoading = false;
            } catch (error) {
                alert(error.message);
            }
        },

        async deleteItem(actionUrl, id) {
            if (!confirm("Hapus data ini?")) return;

            await fetch(`${actionUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            this.fetchData(`${actionUrl}-json`);
        },

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

        numberFormat(number) {
            return Number(number).toLocaleString("id-ID", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            });
        },
    };
}

export function Crudstok() {
    return {
        items: [],
        searchItem: "",
        selectedItem: null,
        openItem: false,

        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: [],
        form: {
            stok: "",
            id: "",
            item: "",
        },
        isEditing: false,
        errors: {},
        success: false,
        isLoading: false,

        fetchData(actionUrl) {
            const method = "GET";
            fetch(actionUrl, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.rows = data;
                    console.log(data);
                });
        },

        fetchItem() {
            const method = "GET";
            fetch("/dashboard/items-json", {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    this.items = data;
                });
        },

        filteredItem() {
            return this.items.filter((item) =>
                item.name.toLowerCase().includes(this.searchItem.toLowerCase())
            );
        },

        selectItem(item) {
            this.selectedItem = item.id;
            this.searchItem = item.name;
            this.openItem = false;
            this.form.item = item.id;
        },

        editItem(item) {
            console.log(item);
            this.form.id = item.id;
            this.form.name = item.name;
            this.form.stok = item.stok;
            this.form.price = item.price;
            this.isEditing = true;
            this.errors = {};
        },

        async formHandler(actionUrl) {
            console.log(this.form);
            this.isLoading = true;
            this.errors = {};
            this.success = false;

            try {
                let url = actionUrl;
                let method = "POST";

                if (this.isEditing) {
                    url = `${actionUrl}/${this.form.id}`;
                    method = "PUT";
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                    body: JSON.stringify(this.form),
                });

                this.isEditing = false;

                if (!response.ok) {
                    if (response.status === 422) {
                        const res = await response.json();
                        this.errors = res.errors || {};
                        this.isLoading = false;
                    } else {
                        throw new Error("Gagal mengirim data.");
                    }
                    return;
                }

                this.form = {
                    item: "",
                    stok: "",
                };
                this.selectCat = null;
                this.selectUnit = null;
                this.success = false;
                this.fetchData(`${actionUrl}-json`);
                this.isLoading = false;
            } catch (error) {
                alert(error.message);
            }
        },

        async deleteItem(actionUrl, id) {
            if (!confirm("Hapus data ini?")) return;

            await fetch(`${actionUrl}/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            this.fetchData(`${actionUrl}-json`);
        },

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
                Object.values(row.items[0].name).some((val) =>
                    String(val)
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                )
            );

            console.log(temp);

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

        numberFormat(number) {
            return Number(number).toLocaleString("id-ID", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            });
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
}

import Chart from "@toast-ui/chart";
import "@toast-ui/chart/dist/toastui-chart.min.css";

export function salesChart() {
    return {
        selectedMonth: new Date().getMonth() + 1,
        selectedYear: new Date().getFullYear(),
        months: [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ],
        years: [],
        dummyData: null,

        async fetchData(actionUrl) {
            const method = "GET";
            fetch(actionUrl, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ),
                },
            })
                .then((res) => res.json())
                .then((da) => {
                    this.years = da.Year;
                    this.dummyData = da.data;
                    this.$nextTick(() => {
                        this.renderChart();
                    });
                });
        },

        chartInstance: null,

        updateChart() {
            this.renderChart();
        },

        renderChart() {
            const dataByYear = this.dummyData[this.selectedYear] || {};
            const categories = Object.keys(dataByYear); // Bulan di Y
            const totals = Object.values(dataByYear); // Total di X

            const chartData = {
                categories: categories,
                series: [
                    {
                        name: "Total Penjualan",
                        data: totals,
                    },
                ],
            };

            const options = {
                chart: {
                    width: 700,
                    height: 400,
                    title: `Grafik Penjualan Tahun ${this.selectedYear}`,
                },
                xAxis: {
                    title: "Bulan",
                },
                yAxis: {
                    title: "Jumlah",
                },
                series: {
                    verticalAlign: true, // ✅ Membuat batang horizontal
                },
                responsive: {
                    animation: true,
                },
            };

            const container = document.getElementById("chart-area");
            container.innerHTML = "";

            this.chartInstance = Chart.columnChart({
                el: container,
                data: chartData,
                options,
            });
        },
    };
}

export function report() {
    return {
        param: null,
        search: "",
        sortColumn: "name",
        sortAsc: true,
        currentPage: 1,
        perPage: 10,
        rows: [],
        form: {
            tahun: "0",
            tipe: "1",
        },
        isEditing: false,
        errors: {},
        success: false,

        async init(defaultTahun, defaultTipe) {
            this.form.tahun = defaultTahun;
            this.form.tipe = defaultTipe;
            await this.fetchData();
        },

        async fetchData() {
            try {
                const response = await fetch(
                    `/dashboard/laporan-json/${this.form.tipe}/${this.form.tahun}`
                );
                const result = await response.json();
                this.rows = result;
            } catch (error) {
                console.error("Fetch error:", error);
            }
        },

        submitForm(event) {
            this.errors = {};
            this.fetchData();
        },

        editItem(item) {
            this.form.id = item.id;
            this.form.app = item.name;
            this.form.name = item.users[0].name;
            this.form.hp = item.users[0].nomor;
            this.form.email = item.users[0].email;
            this.isEditing = true;
            this.userID = item.users[0].id;
            this.errors = {};
        },

        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortColumn = column;
                this.sortAsc = true;
            }
        },

        filteredData() {
            return this.rows;
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

        totalNominal() {
            return this.filteredData().reduce(
                (sum, row) => sum + (parseFloat(row.nominal) || 0),
                0
            );
        },

        totalItem() {
            return this.filteredData().reduce(
                (sum, row) =>
                    sum + (parseFloat(row.items?.price) * row.total_count || 0),
                0
            );
        },

        dateLocal(datetime) {
            let date = new Date(datetime);
            return date.toLocaleDateString("id-ID");
        },

        numberFormat(number) {
            return Number(number).toLocaleString("id-ID", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            });
        },
    };
}
