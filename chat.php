<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan</title>
    <link rel="stylesheet" href="new-assets/boxicons/css/boxicons.min.css">
    <script src="new-assets/talwind/talwind.js"></script>
    <script src="new-assets/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="new-assets/css/ruko.css">
</head>

<body class="bg-gray-50 relative overflow-hidden">
    <!-- Header -->
    <header
        class="md:px-4 sm:px-3 px-2 py-2 flex items-center justify-between border-b fixed bg-white top-0 z-10 sm:w-[500px] w-full left-1/2"
        style="transform: translateX(-50%);">
        <div class="flex items-center justify-between gap-4 ">
            <a href="index.php" target="_parent"
                class="hover:text-[#5f67fa] rounded-full w-[30px] aspect-[1/1] flex justify-center items-center"><i
                    class="bx bx-arrow-back text-2xl"></i></a>
            <a href="#" class="flex items-center gap-2">
                <i class="bx bxs-user-circle bx-sm"></i>
                <span>Nama Penyewa</span>
            </a>
            <small id="peerStatus"></small>
        </div>
        <div class="drop-down relative">
            <a href="#" class="rounded-xl flex justify-center items-center lg:p-1 lg:px-3"
                onclick="$(this).parent().toggleClass('open')">
                <!-- <span class="hidden lg:inline">
                    lainnya
                </span> -->
                <i class="bx bx-dots-horizontal-rounded text-2xl text-[#5f67fa]"></i>
            </a>
            <ul class="drop-down-menu bg-white border absolute right-2 z-10  rounded-lg">
                <li>
                    <a href="#" class="hover:text-[#5f67fa] py-2 px-4 flex gap-3 justify-start items-center truncate">
                        <i class="bx bx-user "></i>
                        <span>Profil Penyewa</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="hover:text-[#5f67fa] py-2 px-4 flex gap-3 justify-start items-center truncate" onclick="hapusChat(chat_key)">
                        <i class="bx bx-trash "></i>
                        <span>Hapus Pesan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="hover:text-[#5f67fa] py-2 px-4 flex gap-3 justify-start items-center truncate">
                        <i class="bx bx-bell "></i>
                        <span>Notifikasi</span>
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <main
        class="md:px-3 sm:px-2 px-1 bg-gray-100 sm:py-2 py-1 h-[100dvh] overflow-y-auto fixed top-[3rem] left-1/2 pb-5 h-[calc(100dvh-3rem)] z-1 sm:w-[500px] w-full"
        style="transform: translateX(-50%);">
        <div class="produk p-2 bg-white rounded rounded-b-[2rem]">
            <div class="flex gap-3 pb-2 ">
                <div
                    class="bg-gray-300 w-[80px] aspect-[1/1] rounded-lg overflow-hidden flex justify-center items-center">
                    <img loading="lazy" src="new-assets/img/kosong.jpg" class="w-full object-cover">
                </div>
                <div class="w-full">
                    <div class="flex justify-between items-center w-full">
                        <h2 class="font-semibold">Rumah Papa Jhons</h2>
                        <div class="flex justify-start items-center">
                            <i class="bx bxs-star text-yellow-400"></i>
                            <i class="bx bxs-star text-yellow-400"></i>
                            <i class="bx bxs-star text-yellow-400"></i>
                            <i class="bx bxs-star-half text-yellow-600"></i>
                            <i class="bx bx-star text-yellow-700"></i>
                        </div>
                    </div>
                    <p class="text-xs">Alamat Lorem ipsum dolor sit amet</p>
                    <p class="text-xs">Telpon 082144733128</p>
                    <h3 class="font-semibold text-sm mt-2">Rp 20.000</h3>
                </div>
            </div>
            <a href="#" class="text-center block py-2 hover:bg-[#5f67fa33] border rounded-full ">Booking Now</a>
        </div>
        <div class="area-chat grid gap-2 pt-2 pb-4 sm:mb-16 mb-10 text-sm"></div>
    </main>
    <footer id="chat" class="fixed bottom-0 bg-white border-r sm:p-2 p-1 shadow-2xl z-10 h-auto sm:w-[500px] w-full left-1/2"
        style="transform: translateX(-50%);">
        <form class="flex gap-2 items-center py-1" method="post" enctype="multipart/form-data" id="formpesan">
            <label for="file" class="flex justify-center items-center w-[30px] aspect-[1/1] border rounded-full bg-black hover:bg-[#5f67fa] text-white">
                <i class="bx bx-image"></i>
            </label>
            <input type="file" name="file" id="file" class="hidden" accept="image/*">
            <textarea name="pesan" id="pesan" rows="1"
                class="border rounded-xl  w-full outline-none p-2 text-left overflow-hidden"
                placeholder="Tulis Pesan..." oninput="$(this).css('height', this.scrollHeight+'px')"></textarea>
            <button type="submit"
                class="flex justify-center items-center bg-black hover:bg-[#5f67fa] w-[30px] aspect-[1/1] rounded-full text-white">
                <i class="bx bx-send"></i>
            </button>
        </form>
    </footer>
    <audio id="soundIncoming" src="new-assets/sound/message-in.mp3" autoplay muted preload="auto"></audio>
    <audio id="soundOutgoing" src="new-assets/sound/send.mp3" autoplay muted preload="auto"></audio>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <script>
        const chat_key = 'kon1';
        const sender_type = 'user';
        const sender_id = '<?php echo isset($_GET['id']) ? $_GET['id'] : '1'; ?>';
        const target_type = 'user';
        const target_id = '<?php echo @$_GET['id'] ? 1 : 2; ?>'; //ganti dengan id peer tujuan
        var last_id = 0;

        //soket.io client

        const socket = io("https://ws-chat-xcode.up.railway.app", {
            transports: ["websocket"]
        });

        // bergabung ke ruang chat tertentu
        socket.emit("join", chat_key);

        // dengar notifikasi pesan baru
        socket.on("notifikasi_pesan", (data) => {
            if (data.chatKey !== chat_key) return; // abaikan kalau bukan untuk chat ini
            if (data.pengirim == sender_id) return; // abaikan kalau pengirim adalah diri sendiri

            if (data.type === 'delete') {
                let areaChat = document.querySelector('.area-chat');
                areaChat.innerHTML = '';
                last_id = 0;
                ambilChat(chat_key, last_id).then(chats => renderAllChat(chats));
                return;
            }
            ambilChat(chat_key, last_id).then(chats => renderAllChat(chats));
            // tampilkan tanda di UI, misalnya titik merah, atau bunyi notifikasi
        });

        // kalau user kirim pesan baru di web utama (PHP)
        function kirimPesanBaru(chat_key, sender_id, type = 'message') {
            socket.emit("pesan_baru", {
                chatKey: chat_key,
                pengirim: sender_id,
                type: type
            });
        }

        async function simpanPesan(chat_key, sender_type, sender_id, text, image = null) {
            const formData = new FormData();
            formData.append('sender_type', sender_type);
            formData.append('sender_id', sender_id);
            formData.append('message', text);
            if (image) formData.append('image', image);
            // if (imageUrl) formData.append('image_url', imageUrl);
            return await fetch(`chat-api.php?aksi=simpan_pesan&chat_key=${chat_key}`, {
                method: 'POST',
                body: formData
            });
        }

        async function ambilChat(chat_key, last_id) {
            const res = await fetch(`chat-api.php?aksi=ambil_chat&chat_key=${chat_key}&last_id=${last_id}`);
            return await res.json();
        }

        function resizeImage(file, maxWidth = 256, maxHeight = 256) {
            return new Promise((resolve) => {
                const img = new Image();
                const reader = new FileReader();

                reader.onload = e => img.src = e.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ratio = Math.min(maxWidth / img.width, maxHeight / img.height);
                    canvas.width = img.width * ratio;
                    canvas.height = img.height * ratio;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    resolve(canvas.toDataURL('image/jpeg', 0.7)); // Base64 kecil
                };
                reader.readAsDataURL(file);
            });
        }

        function waktuChat(time) {
            let r = time;
            let s = new Date();
            let w = new Date(time);
            let [wT, wW] = w.toLocaleString().split(',');
            r = wW.slice(0, 6);
            let wD = w.toDateString();
            if (wD != s.toDateString()) {
                let [h, b, t, y] = wD.split(' ');
                r = [t, b, r].join(' ');
            }
            return r;
        }

        function renderChat(chat) {
            let areaChat = document.querySelector('.area-chat');
            let el = document.createElement('div');
            let clsChild = ``;
            if (chat.sender_type == sender_type &&
                chat.sender_id == sender_id
            ) {
                el.className = 'kirim';
                clsChild = `chat-out max-w-[95%] bg-[#5f67fa] text-white float-right pt-3 pb-1 pl-4 pr-3 rounded-[20px] rounded-br-[0]`;
            } else {
                el.className = 'terima';
                clsChild = `chat-in max-w-[95%] bg-white float-left pt-3 pb-1 pl-4 pr-3 rounded-[20px] rounded-bl-[0]`;
            }
            let del = `<a href="#" class="bg-red-500 ml-3 px-2 py-1 rounded-full text-white hidden" onclick="hapusChat('${chat.chat_key}',${chat.id})"><i class="bx bx-trash"></i></a>`;
            if (el.className == 'kirim') {
                chat.message += del;
            }
            let elChild = `<div id="chat-${chat.id}" class="${clsChild}" oncontextmenu="$(this).find('a').removeClass('hidden'); return false;" onmouseleave="$(this).find('a').addClass('hidden'); return false;">
            <div>${chat.message}</div>
            <time class="text-[11px] float-right">${waktuChat(chat.created_at)}</time>
            </div>`;
            el.innerHTML = elChild;
            if (!areaChat.querySelector(`#chat-${ chat.id}`)) {
                areaChat.appendChild(el);
                if (last_id < chat.id && el.className == 'terima') {
                    playIncomingSound();
                }
                last_id = chat.id;
            }
            scrollBottom();
        }

        function renderAllChat(chats) {
            if (typeof chats === 'object') {
                chats.forEach(chat => {
                    renderChat(chat);
                });
            }
        }

        function loadChat() {
            ambilChat(chat_key, last_id).then(chats => renderAllChat(chats))
            // .finally(
            //     function() {
            //         setTimeout(loadChat, 1000);
            //     }
            // );
        }

        function playIncomingSound() {
            const audio = document.getElementById('soundIncoming');
            if (audio) {
                audio.muted = false;
                audio.currentTime = 0; // mulai dari awal
                audio.volume = 0.6;
                audio.play().catch(err => console.warn('🔇 Audio play blocked:', err));
            }
        }

        function playOutgoingSound() {
            const audio = document.getElementById('soundOutgoing');
            if (audio) {
                audio.muted = false;
                audio.currentTime = 0;
                audio.volume = 0.4;
                audio.play().catch(() => {});
            }
        }

        $(document).ready(function() {
            loadChat();
            $('#formpesan').on('submit', function(e) {
                e.preventDefault();
                simpanPesan(chat_key, sender_type, sender_id, $(this).find('#pesan').val())
                    .then(r => {
                        if (r.status === 200) {
                            r.json().then(j => {
                                if (j && j.status == 'ok') {
                                    ambilChat(chat_key, last_id).then(chats => renderAllChat(chats)).finally(() => {
                                        playOutgoingSound()
                                        kirimPesanBaru(chat_key, sender_id);
                                    })
                                }
                            })
                        } else {
                            alert('Gagal Kirim');
                        }
                    }).finally(() => {
                        $('#formpesan')[0].reset();
                    });
            }).on('keydown', e => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    $('#formpesan').submit();
                }
            });
            document.addEventListener('click', () => {
                const audio = document.getElementById('soundOutgoing');
                if (audio) {
                    audio.muted = false;
                    audio.currentTime = 0;
                    audio.volume = 0.05;
                    audio.play().catch(() => {});
                }
            }, {
                once: true
            });
        });

        function scrollBottom() {
            const main = document.querySelector('main');
            main.scrollTop = main.scrollHeight;
        }

        function hapusChat(chat_key, chat_id = 0) {
            if (confirm(chat_id ? 'Yakin ingin hapus pesan ini?' : 'Yakin ingin hapus percakapan ini?')) {
                fetch(`chat-api.php?aksi=hapus_chat&chat_key=${chat_key}&chat_id=${chat_id}`).then(r => {
                    if (r.status == 200) {
                        r.json().then(j => {
                            if (j.status == 'deleted') {
                                if (chat_id) {
                                    document.querySelector('#chat-' + chat_id).parentElement.remove();
                                    playOutgoingSound();
                                } else {
                                    chats = document.querySelectorAll('.kirim,.terima');
                                    chats.length ? chats.forEach(c => {
                                        c.remove();
                                    }) : '';
                                    last_id = 0;
                                }
                                kirimPesanBaru(chat_key, sender_id, 'delete');
                            }
                        })
                    }
                });
            }
        }
    </script>
</body>

</html>