<?php
/**
 * Media Storage Configuration for Thánh Ca Online
 *
 * - $THANHCA_R2_PUBLIC_URL:
 *   Set this to your Cloudflare R2 public bucket URL (e.g. "https://pub-xxxxxxxx.r2.dev"
 *   or a custom domain like "https://media.chungnhan.org") to stream MP3, PDF, and JPG
 *   files directly from Cloudflare R2.
 *
 * - $THANHCA_PREFER_R2:
 *   If true (and $THANHCA_R2_PUBLIC_URL is set), always serve media from Cloudflare R2
 *   even when local ../../taphat_media/ exists.
 *   If false, use local ../../taphat_media/ when present and automatically fall back
 *   to Cloudflare R2 when local files are absent (e.g. on a cloud host deployed from GitHub).
 */
$THANHCA_R2_PUBLIC_URL = getenv('THANHCA_R2_URL') ?: '';
$THANHCA_PREFER_R2     = true;
