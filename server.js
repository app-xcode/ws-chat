import { Server } from "socket.io";
import express from "express";
import http from "http";

const app = express();
const server = http.createServer(app);

const io = new Server(server, {
  cors: {
    origin: ["https://kontrakan-dili.ct.ws/", "http://localhost"], // domain web kamu
    methods: ["GET", "POST"],
  },
});

io.on("connection", (socket) => {
  console.log("Client connected:", socket.id);

  // Ketika client bergabung ke ruang obrolan
  socket.on("join", (chatKey) => {
    socket.join(chatKey);
    console.log(`${socket.id} join ke chat ${chatKey}`);
  });

  // Saat ada pesan baru di DB, frontend kirim ke WebSocket:
  socket.on("pesan_baru", (data) => {
    const { chatKey, pengirim } = data;
    console.log(`Pesan baru dari ${pengirim} di chat ${chatKey}`);
    // Kirim notifikasi ke semua anggota chat itu (kecuali pengirim)
    socket.to(chatKey).emit("notifikasi_pesan", data);
  });

  socket.on("disconnect", () => {
    console.log("Client disconnect:", socket.id);
  });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => console.log(`Socket.IO running on port ${PORT}`));
